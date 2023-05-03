<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rows = $request->rows;
        $inventory = DB::table('inventory')
        ->latest()
        ->paginate($rows ?? 10)
        ->appends(request()->query());

        return view('pages.admin.inventory.index', ['inventory' => $inventory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne()
    {
        return view('pages.admin.inventory.create.step_one');

    }

    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'quantity' => 'required',
            'remarks' => '',
        ]);

        DB::table('inventory')
        ->insert($formFields);

        return redirect('/inventory/new/step-two');
    }
    
    public function create_StepTwo()
    {
        return view('pages.admin.inventory.create.complete');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = DB::table('inventory')
        ->where('id', '=', $id)
        ->first();

        return view('pages.admin.inventory.show', ['item' => $item, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = DB::table('inventory')
        ->where('id', '=', $id)
        ->first();

        return view('pages.admin.inventory.show', ['item' => $item, 'editing' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'name'=> 'required',
            'quantity' => 'required',
            'remarks' => ''
        ]);

        DB::table('inventory')
        ->where('id', '=', $id)
        ->update($formFields);

        return redirect("/inventory/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
