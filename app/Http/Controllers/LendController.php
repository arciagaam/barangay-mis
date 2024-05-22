<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class LendController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
        View::share('streets', DB::table('streets')->get());
        View::share('sex', DB::table('sex')->orderBy('id')->get());
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
        ->orderBy('lent_items.created_at', 'desc')
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
        $religions = DB::table('religions')->orderBy('id')->get();
        $occupations = DB::table('occupations')->orderBy('id')->get();

        $inventory = DB::table('inventory')
        ->where('archived', 0)
        ->latest()
        ->get();

        return view('pages.admin.inventory.lend.create.step_one', ['item' => $item ?? null, 'inventory' => $inventory, 'religions' => $religions, 'occupations' => $occupations]);
        
    }

    public function post_StepOne(Request $request)
    {

        $formFields = $request->validate([
            'id' => 'required',
            'first_name'  => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'nickname' => '',
            'sex' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'place_of_birth' => 'required',
            'occupation_id' => 'required',
            'religion_id' => 'required',
            'house_number' => 'required',
            'street_id' => 'required',
            'others' => '',
            'voter_status' => '',
            'disabled' => '',
            'contact' => ['required'],
            'return_date' => 'required',
            'quantity' => 'required'
        ]);

        $checkResident = DB::table('residents')
        ->join('households', 'households.id', 'residents.household_id')
        ->where('residents.first_name', $request->first_name)
        ->where('residents.middle_name', $request->middle_name)
        ->where('residents.last_name', $request->last_name)
        ->where('residents.nickname', $request->nickname)
        ->where('residents.sex', $request->sex)
        ->where('residents.birth_date', $request->birth_date)
        ->where('residents.age', $request->age)
        ->where('residents.place_of_birth', $request->place_of_birth)
        ->where('residents.occupation_id', $request->occupation_id)
        ->where('residents.religion_id', $request->religion_id)
        ->where('residents.voter_status', $request->voter_status)
        ->where('residents.disabled', $request->disabled)
        ->where('households.house_number', $request->house_number)
        ->where('households.others', $request->others)
        ->where('households.street_id', $request->street_id)
        ->first();

        if (!$checkResident) {
            $formFields['voter_status'] = lcfirst($request->voter_status) == 'registered' ? 1 : 0;
            $formFields['disabled'] = lcfirst($request->disabled) == 'abled' ? 1 : 0;

            if (empty($request->session()->get('resident'))) {
                $resident = new Resident();
                $resident->fill($formFields);
                $request->session()->put('resident', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household', $household);
            } else {
                $resident = $request->session()->get('resident');
                $resident->fill($request->post());
                $request->session()->put('resident', $resident);

                $household = $request->session()->get('household');
                $household->fill($request->post());
                $request->session()->put('household', $household);
            }

            $request->session()->put('new_resident', true);
        } else {
            $request->session()->forget('resident');
            $request->session()->forget('household');
            $request->session()->forget('new_resident');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }
        }


        if(!$request->id) {
            return back()->with('error', 'Select an item');
        }else if($request->quantity == 0) {
            return back()->with('error', 'Quantity should not be 0. Check if stocks are available');
        }

        $details = [
            'contact' => $formFields['contact'],
            'return_date' => $formFields['return_date'],
            'quantity' => $formFields['quantity'],
        ];

        if ($request->session()->get('new_resident')) {

            $household = $request->session()->get('household');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();

            $recipient = $resident->id;

            addToLog('Create', "New Household Created");
            addToLog('Create', "New Resident Created");
            
            $request->session()->forget('resident');
            $request->session()->forget('household');
            $request->session()->forget('new_resident');   
    
            DB::table('lent_items')
            ->insert(['resident_id' => $recipient, 'inventory_id' => $request->id, 'status' => 0, ...$details]);
        }else {
            DB::table('lent_items')
            ->insert(['resident_id' => $request->resident_id, 'inventory_id' => $request->id, 'status' => 0, ...$details]);
        }

        DB::table('inventory')
        ->where('id', '=', $request->id)
        ->decrement('quantity', intval($request->quantity));

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
