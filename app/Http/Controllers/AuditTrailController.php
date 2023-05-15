<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AuditTrailController extends Controller
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
            $logs = DB::table('logs')
            ->join('users', 'users.id', 'logs.user_id')
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search.'%')
                ->orWhere('middle_name', 'like', $request->search.'%')
                ->orWhere('last_name', 'like', $request->search.'%')
                ->orWhere('username', 'like', $request->search.'%');
            })
            ->latest('logs.created_at')
            ->select([
                'logs.*',
                'users.username',
                'users.first_name',
                'users.middle_name',
                'users.last_name'
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $logs = DB::table('logs')
            ->join('users', 'users.id', 'logs.user_id')
            ->latest()
            ->select([
                'logs.*',
                'users.username',
                'users.first_name',
                'users.middle_name',
                'users.last_name'
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }


        return view('pages.admin.maintenance.audit_trail.index', ['logs' => $logs]);
    }

}
