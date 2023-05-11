<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residents = DB::table('residents')->get();
        $blotter_count = DB::table('blotters')->count();

        $male = $residents->filter(function($value, $key) {
            return($value->sex==1);
        });

        $female = $residents->filter(function($value, $key) {
            return($value->sex==2);
        });

        $voter = $residents->filter(function($value, $key){
            return($value->voter_status==0);
        });

        // GETTING ALL NON-VOTERS RESIDENTS
        $non_voter = $residents->filter(function($value, $key){
            return($value->voter_status==1);
        });

        $toddler = $residents->filter(function($value, $key){
            return($value->age<=3);
        });

        // GETTING ALL NON-VOTERS RESIDENTS
        $senior = $residents->filter(function($value, $key){
            return($value->age>=60);
        });

        $data = [
            'blotter_count' => $blotter_count,
            'residents' => count($residents),
            'male' => count($male),
            'female' => count($female),
            'voter' => count($voter),
            'non_voter' => count($non_voter),
            'toddler' => count($toddler),
            'senior' => count($senior),
        ];

        return view('pages.admin.dashboard.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
