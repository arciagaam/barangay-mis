<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LendController extends Controller
{
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
    public function create_StepOne()
    {
        return view('pages.admin.inventory.lend.create.step_one');
        
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

        return redirect("/inventory/lend/new/step-two");
    }

    public function create_StepTwo()
    {
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

        return view('pages.admin.inventory.lend.show', ['item' => $lentItem, 'editing' => false]);
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

        return view('pages.admin.inventory.lend.show', ['item' => $lentItem, 'editing' => true]);
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

        return redirect("/inventory/lend/$id");
    }

    public function return(Request $request, string $id)
    {

        $lent_item = DB::table('lent_items')
        ->where('id', '=', $id)
        ->first();

        $quantity = $lent_item->quantity;
        $itemId = $lent_item->inventory_id;

        DB::table('inventory')
        ->where('id', '=', $itemId)
        ->increment('quantity', intval($quantity));

        DB::table('lent_items')
        ->where('id', '=', $id)
        ->update(['status' => 1]);

        return redirect("/inventory/lend/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
