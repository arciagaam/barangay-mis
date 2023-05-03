<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.maintenance.archive.index');
    }

    public function residents(Request $request)
    {
        $data = DB::table('residents')
        ->where('archived', '=', 1)
        ->latest()
        ->get();

        return view('pages.admin.maintenance.archive.inventory.index', ['data' => $data]);   
    }

    public function inventory(Request $request)
    {
        
    }

    public function mapping(Request $request)
    {
        
    }

    
}
