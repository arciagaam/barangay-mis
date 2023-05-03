<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlotterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $blotters = DB::table('blotters')
            ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=' , 'blotters.id')
            ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
            ->join('blotter_status', 'blotter_status.id', '=', 'blotters.status_id')
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search.'%')
                ->orWhere('middle_name', 'like', $request->search.'%')
                ->orWhere('last_name', 'like', $request->search.'%')
                ->orWhere('nickname', 'like', $request->search.'%')
                ->orWhere('blotters.incident_type', 'like', $request->search.'%')
                ->orWhere('blotters.incident_place', 'like', $request->search.'%');
            })
            ->orderBy('blotters.created_at', 'asc')
            ->distinct(['blotter_recipients.blotter_id'])
            ->select([
                'blotters.id as blotter_id',
                'blotters.status_id',
                'blotter_status.name as status',
                'blotters.incident_type',
                'blotters.description',
                'blotters.incident_place',
                'blotters.date_time_reported',
                'blotters.date_time_incident'
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $blotters = DB::table('blotters')
            ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=' , 'blotters.id')
            ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
            ->join('blotter_status', 'blotter_status.id', '=', 'blotters.status_id')
            ->orderBy('blotters.created_at', 'asc')
            ->distinct(['blotter_recipients.blotter_id'])
            ->select([
                'blotters.id as blotter_id',
                'blotters.status_id',
                'blotter_status.name as status',
                'blotters.incident_type',
                'blotters.description',
                'blotters.incident_place',
                'blotters.date_time_reported',
                'blotters.date_time_incident'
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        $data = array();
        foreach($blotters as $key => $value){
            array_push($data, $value);
            $recipients = DB::table('blotter_recipients')
            ->where('blotter_recipients.blotter_id', '=', $value->blotter_id)
            ->join('blotter_roles', 'blotter_roles.id', '=', 'blotter_recipients.blotter_role_id')
            ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
            ->select([
                'blotter_recipients.resident_id',
                'residents.first_name',
                'residents.middle_name',
                'residents.last_name',
                'residents.nickname',
                'blotter_recipients.blotter_role_id',
                'blotter_roles.name'
            ])
            ->get();
            $data[$key]->recipients = $recipients;
        }

        return view('pages.admin.blotter.index', ['blotters' => $data, 'blotter_pagination' => $blotters]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne(Request $request)
    {
        if($request->session()->get('blotter.reporter')){
            $residentData = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->join('religions', 'religions.id', '=', 'residents.religion_id')
            ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
            ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
            ->where('residents.archived', '=', '0')
            ->where('residents.id', '=', $request->session()->get('blotter.reporter'))
            ->first();
        }
        


        return view('pages.admin.blotter.create.step_one', ['residentData' => $residentData ?? null]);
    }

    public function post_StepOne(Request $request)
    {
        if(!$request->resident_id) {
            return back()->with('warning', 'Aguy');
        }

        if(!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
            return back()->with('error', 'citizen not found');
        }

        $request->session()->put('blotter.reporter', $request->resident_id);
        return redirect('/blotters/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        return view('pages.admin.blotter.create.step_two', ['residentData' => $residentData ?? null]);
        
    }

    public function post_StepTwo(Request $request)
    {
        if(!$request->resident_id) {
            return back()->with('warning', 'Aguy');
        }

        if(!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
            return back()->with('error', 'citizen not found');
        }

        $request->session()->put('blotter.victim', $request->resident_id);
        return redirect('/blotters/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        return view('pages.admin.blotter.create.step_three', ['residentData' => $residentData ?? null]);
    }

    public function post_StepThree(Request $request)
    {

        if($request->resident_id){
            if(!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'citizen not found');
            }
    
        }
        
        $request->session()->put('blotter.suspect', $request->resident_id ?? null);


        return redirect('/blotters/new/step-four');
    }

    public function create_StepFour(Request $request)
    {
        return view('pages.admin.blotter.create.step_four', ['residentData' => $residentData ?? null]);        
    }

    public function post_StepFour(Request $request)
    {
        $formFields = $request->validate([
            'incident_type' => 'required',
            'incident_place' => 'required',
            'description' => 'required',
            'date_time_incident' => 'required',
        ]);

        $blotter = new Blotter();
        $blotter->fill($formFields);
        $blotter->save();
        $id = $blotter->id;

        $recipients = [$request->session()->get('blotter.reporter'), $request->session()->get('blotter.victim'), $request->session()->get('blotter.suspect')];
        
        foreach($recipients as $index => $recipient) {
            if($recipient == null) continue;
            DB::table('blotter_recipients')->insert(['blotter_id' => $id, 'resident_id' => $recipient, 'blotter_role_id' => $index+1]);
        }

        return redirect('/blotters/new/step-five');
    }

    public function create_StepFive(Request $request)
    {
        return view('pages.admin.blotter.create.complete');        
        
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
        $blotter = DB::table('blotters')
        ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=' , 'blotters.id')
        ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
        ->join('blotter_status', 'blotter_status.id', '=', 'blotters.status_id')
        ->where('blotters.id', '=', $id)
        ->orderBy('blotters.created_at', 'asc')
        ->distinct(['blotter_recipients.blotter_id'])
        ->select([
            'blotters.id as blotter_id',
            'blotters.status_id',
            'blotter_status.name as status',
            'blotters.incident_type',
            'blotters.description',
            'blotters.incident_place',
            'blotters.date_time_reported',
            'blotters.date_time_incident'
        ])
        ->first();

        $recipients = DB::table('blotter_recipients')
        ->where('blotter_recipients.blotter_id', '=', $id)
        ->join('blotter_roles', 'blotter_roles.id', '=', 'blotter_recipients.blotter_role_id')
        ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
        ->select([
            'blotter_recipients.resident_id',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'residents.nickname',
            'blotter_recipients.blotter_role_id',
            'blotter_roles.name'
        ])
        ->get();

        return view('pages.admin.blotter.show', ['blotter' => $blotter, 'recipients' => $recipients, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blotter = DB::table('blotters')
        ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=' , 'blotters.id')
        ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
        ->join('blotter_status', 'blotter_status.id', '=', 'blotters.status_id')
        ->where('blotters.id', '=', $id)
        ->orderBy('blotters.created_at', 'asc')
        ->distinct(['blotter_recipients.blotter_id'])
        ->select([
            'blotters.id as blotter_id',
            'blotters.status_id',
            'blotter_status.name as status',
            'blotters.incident_type',
            'blotters.description',
            'blotters.incident_place',
            'blotters.date_time_reported',
            'blotters.date_time_incident'
        ])
        ->first();

        $recipients = DB::table('blotter_recipients')
        ->where('blotter_recipients.blotter_id', '=', $id)
        ->join('blotter_roles', 'blotter_roles.id', '=', 'blotter_recipients.blotter_role_id')
        ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
        ->select([
            'blotter_recipients.resident_id',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'residents.nickname',
            'blotter_recipients.blotter_role_id',
            'blotter_roles.name'
        ])
        ->get();

        $status = DB::table('blotter_status')
        ->get();

        return view('pages.admin.blotter.show', ['blotter' => $blotter, 'recipients' => $recipients, 'editing' => true, 'status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'status_id' => 'required',
            'date_time_incident' => 'required',
            'date_time_reported' => 'required',
            'incident_place' => 'required',
            'incident_type' => 'required',
            'description' => 'required',
        ]);

        DB::table('blotters')
        ->where('id', '=', $id)
        ->update($formFields);

        return redirect("/blotters/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
