<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Complaint;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ComplaintController extends Controller
{

    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());


        $this->middleware(function ($request, $next) {
            if (str_contains($request->path(), 'complaints/new') && !str_contains($request->path(), 'complaints/new/step-one') && ($request->session()->missing('complaint.complainant') && $request->session()->missing('new_resident.complainant'))) {
                return redirect('/complaints/new/step-one');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->forget('complaint.complainant');
        $request->session()->forget('complaint.defendant');
        $request->session()->forget('resident.complainant');
        $request->session()->forget('resident.defendant');
        $request->session()->forget('new_resident.complainant');
        $request->session()->forget('new_resident.defendant');

        $rows = $request->rows;
        $filter = $request->filter ?? null;


        if ($request->search || $request->search != '') {
            $complaints = DB::table('complaints')
                ->join('complaint_recipients', 'complaint_recipients.complaint_id', '=', 'complaints.id')
                ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
                ->join('blotter_status', 'blotter_status.id', '=', 'complaints.status_id')
                ->where(function ($query) use ($request) {
                    $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                        ->orWhere('first_name', 'like', $request->search . '%')
                        ->orWhere('middle_name', 'like', $request->search . '%')
                        ->orWhere('last_name', 'like', $request->search . '%')
                        ->orWhere('nickname', 'like', $request->search . '%')
                        ->orWhere('complaints.incident_type', 'like', $request->search . '%')
                        ->orWhere('complaints.incident_place', 'like', $request->search . '%');
                })
                ->when($filter != null, function ($query) use ($request, $filter) {
                    switch(lcfirst($filter)) {
                        case 'active' : $query->where("blotter_status.id", 2); break;
                        case 'settled' : $query->where("blotter_status.id", 3); break;
                        case 'rescheduled' : $query->where("blotter_status.id", 4); break;
                        case 'unsettled' : $query->where("blotter_status.id", 1); break;
                    }
                })
                ->orderBy('complaints.created_at', 'asc')
                ->distinct(['complaint_recipients.complaint_id'])
                ->select([
                    'complaints.id as complaint_id',
                    'complaints.status_id',
                    'blotter_status.name as status',
                    'complaints.incident_type',
                    'complaints.description',
                    'complaints.incident_place',
                    'complaints.date_time_reported',
                    'complaints.date_time_incident'
                ])
                ->paginate($rows ?? 10)
                ->appends(request()->query());
        } else {
            $complaints = DB::table('complaints')
                ->join('complaint_recipients', 'complaint_recipients.complaint_id', '=', 'complaints.id')
                ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
                ->join('blotter_status', 'blotter_status.id', '=', 'complaints.status_id')
                ->when($filter != null, function ($query) use ($request, $filter) {
                    switch(lcfirst($filter)) {
                        case 'active' : $query->where("blotter_status.id", 2); break;
                        case 'settled' : $query->where("blotter_status.id", 3); break;
                        case 'rescheduled' : $query->where("blotter_status.id", 4); break;
                        case 'unsettled' : $query->where("blotter_status.id", 1); break;
                    }
                })
                ->orderBy('complaints.created_at', 'asc')
                ->distinct(['complaint_recipients.complaint_id'])
                ->select([
                    'complaints.id as complaint_id',
                    'complaints.status_id',
                    'blotter_status.name as status',
                    'complaints.incident_type',
                    'complaints.description',
                    'complaints.incident_place',
                    'complaints.date_time_reported',
                    'complaints.date_time_incident'
                ])
                ->paginate($rows ?? 10)
                ->appends(request()->query());
        }

        $data = array();
        foreach ($complaints as $key => $value) {
            array_push($data, $value);
            $recipients = DB::table('complaint_recipients')
                ->where('complaint_recipients.complaint_id', '=', $value->complaint_id)
                ->join('complaint_roles', 'complaint_roles.id', '=', 'complaint_recipients.complaint_role_id')
                ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
                ->select([
                    'complaint_recipients.resident_id',
                    'residents.first_name',
                    'residents.middle_name',
                    'residents.last_name',
                    'residents.nickname',
                    'complaint_recipients.complaint_role_id',
                    'complaint_roles.name'
                ])
                ->get();
            $data[$key]->recipients = $recipients;
        }

        $initialComplaints = DB::table('complaints')->get();
        $unresolved_complaints_count = $initialComplaints->filter(function($value, $key) {
            return($value->status_id==1);
        });

        $active_complaints_count = $initialComplaints->filter(function($value, $key) {
            return($value->status_id==2);
        });

        $rescheduled_complaints_count = $initialComplaints->filter(function($value, $key) {
            return($value->status_id==4);
        });

        $settled_complaints_count = $initialComplaints->filter(function($value, $key) {
            return($value->status_id==3);
        });

        $countData = [
            'unresolved_complaints' => count($unresolved_complaints_count),
            'active_complaints' => count($active_complaints_count),
            'rescheduled_complaints' => count($rescheduled_complaints_count),
            'settled_complaints' => count($settled_complaints_count),
        ];

        return view('pages.admin.complaints.index', ['complaints' => $data, 'complaint_pagination' => $complaints, 'countData' => $countData]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne(Request $request)
    {

        if ($request->session()->get('resident.complainant')) {
            $residentData = collect($request->session()->get('resident.complainant'));
            $householdData = collect($request->session()->get('household.complainant'));
            $residentData = json_decode($residentData->toBase()->merge($householdData)->toJson());
        } else if ($request->session()->get('complaint.complainant')) {
            $residentData = DB::table('residents')
                ->join('households', 'households.id', '=', 'residents.household_id')
                ->join('religions', 'religions.id', '=', 'residents.religion_id')
                ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
                ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
                ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
                ->where('residents.archived', '=', '0')
                ->where('residents.id', '=', $request->session()->get('complaint.reporter'))
                ->first();
        }

        return view('pages.admin.complaints.create.step_one', ['residentData' => $residentData ?? null]);
    }

    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'first_name'  => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'nickname' => '',
            'sex' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'house_number' => 'required',
            'purok' => '',
            'block' => '',
            'lot' => '',
            'others' => '',
            'subdivision' => '',
        ]);

        $checkResident = DB::table('residents')
            ->where('residents.first_name', $request->first_name)
            ->where('residents.middle_name', $request->middle_name)
            ->where('residents.last_name', $request->last_name)
            ->where('residents.nickname', $request->nickname)
            ->where('residents.sex', $request->sex)
            ->where('residents.birth_date', $request->birth_date)
            ->where('residents.age', $request->age)
            ->first();

        if (!$checkResident) {
            if (empty($request->session()->get('resident.complainant'))) {
                $resident = new Resident();
                $resident->fill($request->post());
                $request->session()->put('resident.complainant', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household.complainant', $household);
            } else {
                $resident = $request->session()->get('resident.complainant');
                $resident->fill($request->post());
                $request->session()->put('resident.complainant', $resident);

                $household = $request->session()->get('household.complainant');
                $household->fill($request->post());
                $request->session()->put('household.complainant', $household);
            }

            $request->session()->put('new_resident.complainant', true);
        } else {
            $request->session()->forget('resident.complainant');
            $request->session()->forget('new_resident.complainant');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('complaint.reporter', $request->resident_id);
        }

        return redirect('/complaints/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        if ($request->session()->get('resident.defendant')) {
            $residentData = collect($request->session()->get('resident.defendant'));
            $householdData = collect($request->session()->get('household.defendant'));
            $residentData = json_decode($residentData->toBase()->merge($householdData)->toJson());
        } else if ($request->session()->get('complaint.defendant')) {
            $residentData = DB::table('residents')
                ->join('households', 'households.id', '=', 'residents.household_id')
                ->join('religions', 'religions.id', '=', 'residents.religion_id')
                ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
                ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
                ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
                ->where('residents.archived', '=', '0')
                ->where('residents.id', '=', $request->session()->get('complaint.defendant'))
                ->first();
        }

        return view('pages.admin.complaints.create.step_two', ['residentData' => $residentData ?? null]);
    }

    public function post_StepTwo(Request $request)
    {
        $formFields = $request->validate([
            'first_name'  => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'nickname' => '',
            'sex' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'house_number' => 'required',
            'purok' => '',
            'block' => '',
            'lot' => '',
            'others' => '',
            'subdivision' => '',
        ]);

        $checkResident = DB::table('residents')
            ->join('households', 'households.id', 'residents.household_id')
            ->where('residents.first_name', $request->first_name)
            ->where('residents.middle_name', $request->middle_name)
            ->where('residents.last_name', $request->last_name)
            ->where('residents.nickname', $request->nickname)
            ->where('residents.sex', $request->sex)
            ->where('residents.birth_date', $request->birth_date)
            ->where('residents.age', $request->age)
            ->where('households.house_number', $request->house_number)
            ->where('households.purok', $request->purok)
            ->where('households.block', $request->block)
            ->where('households.lot', $request->lot)
            ->where('households.others', $request->others)
            ->where('households.subdivision', $request->subdivision)
            ->first();

        if (!$checkResident) {
            if (empty($request->session()->get('resident.defendant'))) {
                $resident = new Resident();
                $resident->fill($request->post());
                $request->session()->put('resident.defendant', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household.defendant', $household);
            } else {
                $resident = $request->session()->get('resident.defendant');
                $resident->fill($request->post());
                $request->session()->put('resident.defendant', $resident);

                $household = $request->session()->get('household.defendant');
                $household->fill($request->post());
                $request->session()->put('household.defendant', $household);
            }

            $request->session()->put('new_resident.defendant', true);
        } else {
            $request->session()->forget('resident.defendant');
            $request->session()->forget('new_resident.defendant');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('complaint.defendant', $request->resident_id);
        }

        return redirect('/complaints/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        return view('pages.admin.complaints.create.step_three', ['residentData' => $residentData ?? null]);
    }

    public function post_StepThree(Request $request)
    {
        $formFields = $request->validate([
            'incident_type' => 'required',
            'incident_place' => 'required',
            'description' => '',
            'date_time_incident' => 'required',
        ]);

        $complaint = new Complaint();
        $complaint->fill($formFields);
        $complaint->save();
        $complaint_id = $complaint->id;

        if ($request->session()->get('new_resident.complainant')) {
            $household = $request->session()->get('household.complainant');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident.complainant');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();

            addToLog('Create', "New Resident Created");


            $recipient = $resident->id;
            DB::table('complaint_recipients')->insert(['complaint_id' => $complaint_id, 'resident_id' => $recipient, 'complaint_role_id' => 1]);
            $request->session()->forget('blotter.complainant');
        }

        if ($request->session()->get('new_resident.defendant')) {
            $household = $request->session()->get('household.defendant');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident.defendant');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();

            addToLog('Create', "New Resident Created");


            $recipient = $resident->id;
            DB::table('complaint_recipients')->insert(['complaint_id' => $complaint_id, 'resident_id' => $recipient, 'complaint_role_id' => 2]);
            $request->session()->forget('blotter.defendant');
        }

        $recipients = [$request->session()->get('complaint.complainant'), $request->session()->get('complaint.defendant')];
        foreach ($recipients as $index => $recipient) {
            if ($recipient == null) continue;
            DB::table('complaint_recipients')->insert(['complaint_id' => $complaint_id, 'resident_id' => $recipient, 'complaint_role_id' => $index + 1]);
        }

        $hearings = [$request->first_hearing, $request->second_hearing, $request->third_hearing];
        foreach ($hearings as $index => $hearing) {
            if ($hearing == null) continue;
            DB::table('complaint_hearings')->insert(['number' => $index + 1, 'complaint_id' => $complaint_id, 'status_id' => 2, 'date' => $hearing]);
        }


        return redirect('/complaints/new/step-four');
    }

    public function create_StepFour(Request $request)
    {
        $request->session()->forget('complaint.complainant');
        $request->session()->forget('complaint.defendant');
        $request->session()->forget('resident.complainant');
        $request->session()->forget('resident.defendant');
        $request->session()->forget('new_resident.complainant');
        $request->session()->forget('new_resident.defendant');
        addToLog('Create', 'Issued Complaint');
        return view('pages.admin.complaints.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = DB::table('complaints')
            ->join('complaint_recipients', 'complaint_recipients.complaint_id', '=', 'complaints.id')
            ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
            ->join('blotter_status', 'blotter_status.id', '=', 'complaints.status_id')
            ->where('complaints.id', '=', $id)
            ->orderBy('complaints.created_at', 'asc')
            ->distinct(['complaint_recipients.complaint_id'])
            ->select([
                'complaints.id as complaint_id',
                'complaints.status_id',
                'blotter_status.name as status',
                'complaints.incident_type',
                'complaints.description',
                'complaints.incident_place',
                'complaints.date_time_reported',
                'complaints.date_time_incident'
            ])
            ->first();

        $recipients = DB::table('complaint_recipients')
            ->where('complaint_recipients.complaint_id', '=', $id)
            ->join('complaint_roles', 'complaint_roles.id', '=', 'complaint_recipients.complaint_role_id')
            ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
            ->select([
                'complaint_recipients.resident_id',
                'residents.first_name',
                'residents.middle_name',
                'residents.last_name',
                'residents.nickname',
                'complaint_recipients.complaint_role_id',
                'complaint_roles.name'
            ])
            ->get();

        $hearings = DB::table('complaint_hearings')
            ->join('complaints', 'complaints.id', 'complaint_hearings.complaint_id')
            ->join('blotter_status', 'blotter_status.id', 'complaint_hearings.status_id')
            ->where('complaints.id', $complaint->complaint_id)
            ->orderBy('number', 'asc')
            ->get(['complaint_hearings.*', 'complaints.*', 'blotter_status.name as status']);

        return view('pages.admin.complaints.show', ['complaint' => $complaint, 'recipients' => $recipients, 'hearings' => $hearings, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $complaint = DB::table('complaints')
            ->join('complaint_recipients', 'complaint_recipients.complaint_id', '=', 'complaints.id')
            ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
            ->join('blotter_status', 'blotter_status.id', '=', 'complaints.status_id')
            ->where('complaints.id', '=', $id)
            ->orderBy('complaints.created_at', 'asc')
            ->distinct(['complaint_recipients.complaint_id'])
            ->select([
                'complaints.id as complaint_id',
                'complaints.status_id',
                'blotter_status.name as status',
                'complaints.incident_type',
                'complaints.description',
                'complaints.incident_place',
                'complaints.date_time_reported',
                'complaints.date_time_incident'
            ])
            ->first();

        $recipients = DB::table('complaint_recipients')
            ->where('complaint_recipients.complaint_id', '=', $id)
            ->join('complaint_roles', 'complaint_roles.id', '=', 'complaint_recipients.complaint_role_id')
            ->join('residents', 'residents.id', '=', 'complaint_recipients.resident_id')
            ->select([
                'complaint_recipients.resident_id',
                'residents.first_name',
                'residents.middle_name',
                'residents.last_name',
                'residents.nickname',
                'complaint_recipients.complaint_role_id',
                'complaint_roles.name'
            ])
            ->get();

        $hearings = DB::table('complaint_hearings')
            ->join('complaints', 'complaints.id', 'complaint_hearings.complaint_id')
            ->join('blotter_status', 'blotter_status.id', 'complaint_hearings.status_id')
            ->where('complaints.id', $complaint->complaint_id)
            ->orderBy('number', 'asc')
            ->get(['complaint_hearings.*', 'complaints.*', 'blotter_status.name as status', 'complaint_hearings.status_id as status_id']);
        
        $status = DB::table('blotter_status')
        ->get();

        return view('pages.admin.complaints.show', ['complaint' => $complaint, 'recipients' => $recipients, 'hearings' => $hearings, 'editing' => true, 'status' => $status]);
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
            'description' => '',
        ]);

        DB::table('complaints')
            ->where('id', '=', $id)
            ->update($formFields);

        addToLog('Update', "Updated Complaint ID: $id");

        if($request->first_hearing_status_id && $request->first_hearing_date) {
            DB::table('complaint_hearings')->updateOrInsert(['complaint_id' => $id, 'number' => 1], ['status_id' => $request->first_hearing_status_id, 'date' => $request->first_hearing_date]);
            addToLog('Update', "Updated First Hearing Details of Complaint ID: $id");
        }

        if($request->second_hearing_status_id && $request->second_hearing_date) {
            DB::table('complaint_hearings')->updateOrInsert(['complaint_id' => $id, 'number' => 2], ['status_id' => $request->second_hearing_status_id, 'date' => $request->second_hearing_date]);
            addToLog('Update', "Updated Second Hearing Details of Complaint ID: $id");
        }

        if($request->third_hearing_status_id && $request->third_hearing_date) {
            DB::table('complaint_hearings')->updateOrInsert(['complaint_id' => $id, 'number' => 3], ['status_id' => $request->third_hearing_status_id, 'date' => $request->third_hearing_date]);
            addToLog('Update', "Updated Third Hearing Details of Complaint ID: $id");
        }

        return redirect("/complaints/$id");
    }
}
