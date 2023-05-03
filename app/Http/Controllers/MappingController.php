<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MappingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.mapping.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $mappings = DB::table('residents')
        ->join('households', 'households.id', '=', 'residents.household_id')
        ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
        ->select(['residents.id as resident_id',  
        'residents.first_name',
        'residents.middle_name',
        'residents.last_name',
        'mappings.id',
        'mappings.longitude',
        'mappings.latitude',
        'households.house_number',
        'households.purok',
        'households.block',
        'households.lot',
        'households.others',
        'households.subdivision',
        ])
        ->orderBy('residents.created_at', 'desc')
        ->orderBy('residents.id', 'asc')
        ->paginate(10);

        return view('pages.admin.mapping.create', ['mappings' => $mappings]);   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $residentId = $request->residentId;
        $longitude = $request->longitude;
        $latitude = $request->latitude;

        DB::table('mappings')->insert(['resident_id' => $residentId, 'longitude' => $longitude, 'latitude' => $latitude]);

        return response()->json(['success' => 'success'], 200);
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
    public function update(Request $request)
    {
        $residentId = $request->residentId;
        $mappingId = $request->mappingId;
        $longitude = $request->longitude;
        $latitude = $request->latitude;

        DB::table('mappings')
        ->where('id', '=', $mappingId)
        ->update(['longitude' => $longitude, 'latitude' => $latitude]);

        return response()->json(['success' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function list()
    {
        $mappings = DB::table('residents')
        ->join('households', 'households.id', '=', 'residents.household_id')
        ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
        ->select(['residents.id as resident_id',  
        'residents.first_name',
        'residents.middle_name',
        'residents.last_name',
        'mappings.id',
        'mappings.longitude',
        'mappings.latitude',
        'households.house_number',
        'households.purok',
        'households.block',
        'households.lot',
        'households.others',
        'households.subdivision',
        ])
        ->where('mappings.longitude', '!=', null)
        ->orWhere('mappings.longitude', '!=', '')
        ->orderBy('residents.created_at', 'desc')
        ->orderBy('residents.id', 'asc')
        ->paginate(10);

        return view('pages.admin.mapping.list', ['mappings' => $mappings]);   
    }
}
