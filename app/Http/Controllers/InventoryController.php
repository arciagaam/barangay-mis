<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class InventoryController extends Controller
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

        if($request->search || $request->search != '') {
            $inventory = DB::table('inventory')
            ->where('archived', 0)
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search.'%')
                ->orWhere('quantity', 'like', $request->search.'%')
                ->orWhere('remarks', 'like', $request->search.'%');
            })
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }else {
            $inventory = DB::table('inventory')
            ->where('archived', 0)
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }


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

    public function archive(string $id)
    {
        DB::table('inventory')
        ->where('id', $id)
        ->update(['archived' => 1]);

        return back();
    }

    public function recover(string $id)
    {
        DB::table('inventory')
        ->where('id', $id)
        ->update(['archived' => 0]);

        return back();
    }
}
