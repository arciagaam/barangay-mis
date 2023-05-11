<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
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
        return view('pages.admin.maintenance.archive.index');
    }

    public function residents(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $data = DB::table('residents')
            ->where('archived', '=', 1)
            ->where(function($query) use ($request){
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', '%' . $request->search . '%')
                ->orWhere('first_name', 'like', '%'.$request->search.'%')
                ->orWhere('middle_name', 'like', '%'.$request->search.'%')
                ->orWhere('last_name', 'like', '%'.$request->search.'%')
                ->orWhere('nickname', 'like', '%'.$request->search.'%')
                ->orWhere('birth_date', 'like', '%'.$request->search.'%')
                ->orWhere('place_of_birth', 'like', '%'.$request->search.'%');
            })
            ->latest()
            ->paginate($rows ?? 10)
            ->appends($request->query());
        } else {
            $data = DB::table('residents')
            ->where('archived', '=', 1)
            ->latest()
            ->paginate($rows ?? 10)
            ->appends($request->query());
        }

        return view('pages.admin.maintenance.archive.residents.index', ['data' => $data]);   
    }

    public function inventory(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $inventory = DB::table('inventory')
            ->where('archived', 1)
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
            ->where('archived', 1)
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.archive.inventory.index', ['inventory' => $inventory]);   

    }

    public function mapping(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
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
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search.'%')
                ->orWhere('middle_name', 'like', $request->search.'%')
                ->orWhere('last_name', 'like', $request->search.'%')
                ->orWhere('nickname', 'like', $request->search.'%')
                ->orWhere('birth_date', 'like', $request->search.'%')
                ->orWhere('place_of_birth', 'like', $request->search.'%')
                ->orWhere('house_number', 'like', $request->search.'%')
                ->orWhere('purok', 'like', $request->search.'%')
                ->orWhere('block', 'like', $request->search.'%')
                ->orWhere('lot', 'like', $request->search.'%')
                ->orWhere('others', 'like', $request->search.'%')
                ->orWhere('subdivision', 'like', $request->search.'%');
            })
            ->where(function($query) use ($request) {
                $query->where('mappings.longitude', '!=', null)
                ->orWhere('mappings.longitude', '!=', '');
            })
            ->where('mappings.archived', 1)
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }else {
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
            ->where('mappings.archived', 1)
            ->where(function($query) use ($request) {
                $query->where('mappings.longitude', '!=', null)
                ->orWhere('mappings.longitude', '!=', '');
            })
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.archive.mapping.index', ['mappings' => $mappings]);   
    }

    
}
