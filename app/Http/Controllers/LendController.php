<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class LendController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $rows = $request->rows;

        $lentItems = DB::table('lent_items')
        ->join('residents', 'residents.id', '=', 'lent_items.resident_id')
        ->join('inventory', 'inventory.id', '=', 'lent_items.inventory_id')
        ->select([
            'lent_items.id',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'inventory.name as item_name',
            'lent_items.status as status',
            'lent_items.quantity as quantity',
            'lent_items.contact as contact',
        ])
        ->paginate($rows ?? 10)
        ->appends(request()->query());

        return view('pages.admin.inventory.lend.index', ['lent_items' => $lentItems]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne(Request $request, $id = null)
    {
        if($id) {
            $request->session()->put('lend_item', $id);

            $item = DB::table('inventory')
            ->where('id', $id)
            ->first();

            if(!$item) {
                return redirect('/lend/new/step-one/');
            }
        }

        $inventory = DB::table('inventory')
        ->where('archived', 0)
        ->latest()
        ->get();

        return view('pages.admin.inventory.lend.create.step_one', ['item' => $item ?? null, 'inventory' => $inventory]);
        
    }

    public function post_StepOne(Request $request)
    {
        if(!$request->resident_id && !$request->id) {
            return back()->with('error', 'Select an item and borrower');
        }else if(!$request->resident_id) {
            return back()->with('error', 'Select borrower');
        }else if(!$request->id) {
            return back()->with('error', 'Select an item');
        }else if($request->quantity == 0) {
            return back()->with('error', 'Quantity should not be 0. Check if stocks are available');
        }

        $formFields = $request->validate([
            'contact' => ['required', 'numeric'],
            'return_date' => 'required',
            'quantity' => 'required'
        ]);

        DB::table('inventory')
        ->where('id', '=', $request->id)
        ->decrement('quantity', intval($request->quantity));

        DB::table('lent_items')
        ->insert(['resident_id' => $request->resident_id, 'inventory_id' => $request->id, 'status' => 0, ...$formFields]);

        $request->session()->put('item_id', $request->id);
        
        return redirect("/lend/new/step-two");
    }

    public function create_StepTwo(Request $request)
    {
        $id = $request->session()->get('item_id', $request->id);
        addToLog('Lend', "Item ID: $id");
        $request->session()->forget('item_id', $request->id);
        return view('pages.admin.inventory.lend.create.complete');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lentItem = DB::table('lent_items')
        ->join('residents', 'residents.id', '=', 'lent_items.resident_id')
        ->join('inventory', 'inventory.id', '=', 'lent_items.inventory_id')
        ->where('lent_items.id', '=', $id)
        ->select([
            'lent_items.id',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'inventory.name as item_name',
            'inventory.remarks as item_remarks',
            'lent_items.status as status',
            'lent_items.quantity as quantity',
            'lent_items.contact as contact',
            'lent_items.remarks as remarks',
            'lent_items.created_at as lend_date',
            'lent_items.return_date as return_date',
        ])
        ->first();

        if($lentItem->status == 1) {
            $returnedData = DB::table('returned_items')
            ->where('lent_item_id', $lentItem->id)
            ->first();
        }

        return view('pages.admin.inventory.lend.show', ['item' => $lentItem, 'returnedData' => $returnedData ?? null, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lentItem = DB::table('lent_items')
        ->join('residents', 'residents.id', '=', 'lent_items.resident_id')
        ->join('inventory', 'inventory.id', '=', 'lent_items.inventory_id')
        ->where('lent_items.id', '=', $id)
        ->select([
            'lent_items.id',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'inventory.name as item_name',
            'inventory.remarks as item_remarks',
            'lent_items.status as status',
            'lent_items.quantity as quantity',
            'lent_items.contact as contact',
            'lent_items.remarks as remarks',
            'lent_items.created_at as lend_date',
            'lent_items.return_date as return_date',
        ])
        ->first();

        return view('pages.admin.inventory.lend.show', ['item' => $lentItem, 'editing' => true, 'returnedData' => null]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'remarks' => '',
            'contact' => 'required',
            'return_date' => 'required',
        ]);

        DB::table('lent_items')
        ->where('id', '=', $id)
        ->update($formFields);

        addToLog('Update', "Lent Item ID: $id Updated");

        return redirect("/lend/$id");
    }

    public function return(Request $request, string $id)
    {
        
        $validator = Validator::make($request->post(), [
            'returned_quantity' => 'required',
            'remarks' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lent_item = DB::table('lent_items')
        ->where('id', '=', $id)
        ->first();

        if($lent_item->quantity < $request->returned_quantity) {
            return response()->json(['returned_quantity' => 'Return quantity should not be higher than the borrowed quantity'], 422);
        }

        DB::table('inventory')
        ->where('id', '=', $lent_item->inventory_id)
        ->increment('quantity', intval($request->returned_quantity));


        DB::table('lent_items')
        ->where('id', '=', $id)
        ->update(['status' => 1]);

        DB::table('returned_items')
        ->insert(['lent_item_id' => $id, 'returned_quantity' => $request->returned_quantity, 'remarks' => $request->remarks]);

        return redirect("/lend/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
