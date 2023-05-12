<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CalendarController extends Controller
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
        return json_encode(['test' =>  'test']);
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
        $activity = DB::table('activities')
        ->where('id', $id)
        ->first();

        return view('pages.admin.calendar.show', ['activity' => $activity, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $activity = DB::table('activities')
        ->where('id', $id)
        ->first();

        return view('pages.admin.calendar.show', ['activity' => $activity, 'editing' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'start_date' => 'required',
            'end_date' => '',
            'start_time' => '',
            'end_time' => '',
            'name' => 'required',
            'description' => 'required',
        ]);

        $formFields['is_all_day'] = 0;
        if($request->all_day) {
            $formFields['is_all_day'] = 1;
        }

        if($formFields['is_all_day'] == 0) {
            $request->validate([
                'start_time' => 'required',
                'end_time' => 'required',
            ]);
        }

        DB::table('activities')
        ->where('id', $id)
        ->update($formFields);

        return redirect("/calendar/activity/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        DB::table('activities')
        ->where('id', $request->id)
        ->delete();

        return redirect("/dashboard");

    }
}
