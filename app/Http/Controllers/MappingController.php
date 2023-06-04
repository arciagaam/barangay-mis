<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class MappingController extends Controller
{


    public function __construct()
    {
        View::share('reasons',  DB::table('archive_reasons')->latest()->get());
        View::share('barangayInformation', DB::table('barangay_information')->first());
    }
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

        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $mappings = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('streets', 'streets.id', 'households.street_id')
            ->select(['residents.id as resident_id',  
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'mappings.id',
            'mappings.longitude',
            'mappings.latitude',
            'households.house_number',
            'households.others',
            'streets.name as street'
            ])
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search.'%')
                ->orWhere('middle_name', 'like', $request->search.'%')
                ->orWhere('last_name', 'like', $request->search.'%')
                ->orWhere('nickname', 'like', $request->search.'%')
                ->orWhere('birth_date', 'like', $request->search.'%')
                ->orWhere('place_of_birth', 'like', $request->search.'%')
                ->orWhere('house_number', 'like', $request->search.'%')
                ->orWhere('street', 'like', $request->search.'%')
                ->orWhere('others', 'like', $request->search.'%');
            })
            ->where('residents.archived', 0)
            ->orderBy('mappings.longitude', 'desc')
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }else {
            $mappings = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('streets', 'streets.id', 'households.street_id')
            ->select(['residents.id as resident_id',  
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'mappings.id',
            'mappings.longitude',
            'mappings.latitude',
            'households.house_number',
            'households.others',
            'streets.name as street',
            ])
            ->where('residents.archived', 0)
            ->orderBy('mappings.longitude', 'desc')
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

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

        // sineset yung mapping ng resident
        DB::table('mappings')->insert(['resident_id' => $residentId, 'longitude' => $longitude, 'latitude' => $latitude]);
        addToLog('Create', "Mapping Created for Resident ID: $residentId");
        return response()->json(['success' => 'success'], 200);
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

        addToLog('Update', "Mapping ID: $mappingId Updated");
        return response()->json(['success' => 'success'], 200);
    }

    public function list(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $mappings = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('streets', 'streets.id', 'households.street_id')
            ->select(['residents.id as resident_id',  
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'mappings.id',
            'mappings.longitude',
            'mappings.latitude',
            'households.house_number',
            'households.others',
            'streets.name as street',
            ])
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search.'%')
                ->orWhere('middle_name', 'like', $request->search.'%')
                ->orWhere('last_name', 'like', $request->search.'%')
                ->orWhere('nickname', 'like', $request->search.'%')
                ->orWhere('birth_date', 'like', $request->search.'%')
                ->orWhere('place_of_birth', 'like', $request->search.'%')
                ->orWhere('house_number', 'like', $request->search.'%')
                ->orWhere('street', 'like', $request->search.'%')
                ->orWhere('others', 'like', $request->search.'%');
            })
            ->where(function($query) use ($request) {
                $query->where('mappings.longitude', '!=', null)
                ->orWhere('mappings.longitude', '!=', '');
            })
            ->where('residents.archived', 0)
            ->where('mappings.archived', 0)
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }else {
            $mappings = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('streets', 'streets.id', 'households.street_id')
            ->select(['residents.id as resident_id',  
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'mappings.id',
            'mappings.longitude',
            'mappings.latitude',
            'households.house_number',
            'households.others',
            'streets.name as street',
            ])
            ->where('residents.archived', 0)
            ->where('mappings.archived', 0)
            ->where(function($query) use ($request) {
                $query->where('mappings.longitude', '!=', null)
                ->orWhere('mappings.longitude', '!=', '');
            })
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.mapping.list', ['mappings' => $mappings]);   
    }

    public function archive(string $id, Request $request)
    {
        DB::table('mappings')
        ->where('id', '=', $id)
        ->update(['archived' => 1, 'archive_reason_id' => $request->reason]);
        addToLog('Archive', "Mapping ID: $id Archived");
        return back();
    }

    public function recover(string $id)
    {
        DB::table('mappings')
        ->where('id', '=', $id)
        ->update(['archived' => 0]);

        addToLog('Recover', "Mapping ID: $id Recovered");
        return back();
    }
}
