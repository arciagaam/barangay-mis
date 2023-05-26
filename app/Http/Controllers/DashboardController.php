<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use PDO;

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
        $residents = DB::table('residents')
        ->where('archived', 0)
        ->get();

        $initialBlotter = DB::table('blotters');
        $blotter_count = $initialBlotter->count();
        $blotters = $initialBlotter->get();

        $initialComplaints = DB::table('complaints');
        $complaints_count = $initialComplaints->count();
        $complaints = $initialComplaints->get();


        $unresolved_blotters_count = $blotters->filter(function($value, $key) {
            return($value->status_id==1);
        });

        $active_blotters_count = $blotters->filter(function($value, $key) {
            return($value->status_id==2);
        });

        $rescheduled_blotters_count = $blotters->filter(function($value, $key) {
            return($value->status_id==4);
        });

        
        $unresolved_complaints_count = $complaints->filter(function($value, $key) {
            return($value->status_id==1);
        });

        $active_complaints_count = $complaints->filter(function($value, $key) {
            return($value->status_id==2);
        });

        $rescheduled_complaints_count = $complaints->filter(function($value, $key) {
            return($value->status_id==4);
        });

        $male = $residents->filter(function($value, $key) {
            return($value->sex==1);
        });

        $female = $residents->filter(function($value, $key) {
            return($value->sex==2);
        });


        $voter = $residents->filter(function($value, $key){
            return($value->voter_status==0);
        });

        $non_voter = $residents->filter(function($value, $key){
            return($value->voter_status==1);
        });


        $toddler = $residents->filter(function($value, $key){
            return($value->age<=3);
        });

        $senior = $residents->filter(function($value, $key){
            return($value->age>=60);
        });

        $data = [
            'blotter_count' => $blotter_count,
            'unresolved_blotters' => count($unresolved_blotters_count),
            'active_blotters' => count($active_blotters_count),
            'rescheduled_blotters' => count($rescheduled_blotters_count),
            'complaints_count' => $complaints_count,
            'unresolved_complaints' => count($unresolved_complaints_count),
            'active_complaints' => count($active_complaints_count),
            'rescheduled_complaints' => count($rescheduled_complaints_count),
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

}
