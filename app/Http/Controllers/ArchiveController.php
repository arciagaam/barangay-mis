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
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'residents.archive_reason_id')
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
            ->select('residents.*', 'archive_reasons.name as reason')
            ->latest('residents.created_at')
            ->paginate($rows ?? 10)
            ->appends($request->query());
        } else {
            $data = DB::table('residents')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'residents.archive_reason_id')
            ->where('archived', '=', 1)
            ->select('residents.*', 'archive_reasons.name as reason')
            ->latest('residents.created_at')
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
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'inventory.archive_reason_id')
            ->where('archived', 1)
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search.'%')
                ->orWhere('quantity', 'like', $request->search.'%')
                ->orWhere('remarks', 'like', $request->search.'%');
            })
            ->select('inventory.*', 'archive_reasons.name as reason')
            ->latest('inventory.created_at')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }else {
            $inventory = DB::table('inventory')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'inventory.archive_reason_id')
            ->where('archived', 1)
            ->select('inventory.*', 'archive_reasons.name as reason')
            ->latest('inventory.created_at')
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
            ->leftJoin('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'mappings.archive_reason_id')
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
            'archive_reasons.name as reason'
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
            ->leftJoin('households', 'households.id', '=', 'residents.household_id')
            ->leftJoin('mappings', 'mappings.resident_id', '=', 'residents.id')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'mappings.archive_reason_id')
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
            'archive_reasons.name as reason'
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

    public function activity(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $activities = DB::table('activities')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'activities.archive_reason_id')
            ->where('archived', 1)
            ->where(function($query) use ($request) {
                $query->where('activities.name', 'like', $request->search.'%')
                ->orWhere('start_date', 'like', $request->search.'%')
                ->orWhere('end_date', 'like', $request->search.'%')
                ->orWhere('archive_reasons.name', 'like', $request->search.'%');
            })
            ->select('archive_reasons.name as reason', 'activities.*')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $activities = DB::table('activities')
            ->leftJoin('archive_reasons', 'archive_reasons.id', 'activities.archive_reason_id')
            ->select('archive_reasons.name as reason', 'activities.*')
            ->where('archived', 1)
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.archive.activity.index', ['activities' => $activities]);   


    }

    
}
