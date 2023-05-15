<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use LOGS;

class BlotterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());


        $this->middleware(function ($request, $next) {
            if (str_contains($request->path(), 'blotters/new') && !str_contains($request->path(), 'blotters/new/step-one') && ($request->session()->missing('blotter.reporter') && $request->session()->missing('new_resident.reporter'))) {
                return redirect('/blotters/new/step-one');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $request->session()->forget('blotter.reporter');
        $request->session()->forget('blotter.victim');
        $request->session()->forget('blotter.suspect');
        $request->session()->forget('resident.reporter');
        $request->session()->forget('resident.victim');
        $request->session()->forget('resident.suspect');
        $request->session()->forget('new_resident.reporter');
        $request->session()->forget('new_resident.victim');
        $request->session()->forget('new_resident.suspect');

        $rows = $request->rows;

        if ($request->search || $request->search != '') {
            $blotters = DB::table('blotters')
                ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=', 'blotters.id')
                ->join('residents', 'residents.id', '=', 'blotter_recipients.resident_id')
                ->join('blotter_status', 'blotter_status.id', '=', 'blotters.status_id')
                ->where(function ($query) use ($request) {
                    $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                        ->orWhere('first_name', 'like', $request->search . '%')
                        ->orWhere('middle_name', 'like', $request->search . '%')
                        ->orWhere('last_name', 'like', $request->search . '%')
                        ->orWhere('nickname', 'like', $request->search . '%')
                        ->orWhere('blotters.incident_type', 'like', $request->search . '%')
                        ->orWhere('blotters.incident_place', 'like', $request->search . '%');
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
                ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=', 'blotters.id')
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
        foreach ($blotters as $key => $value) {
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

        if ($request->session()->get('resident.reporter')) {
            $residentData = collect($request->session()->get('resident.reporter'));
            $householdData = collect($request->session()->get('household.reporter'));
            $residentData = json_decode($residentData->toBase()->merge($householdData)->toJson());
        } else if ($request->session()->get('blotter.reporter')) {
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
            if (empty($request->session()->get('resident.reporter'))) {
                $resident = new Resident();
                $resident->fill($request->post());
                $request->session()->put('resident.reporter', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household.reporter', $household);
            } else {
                $resident = $request->session()->get('resident.reporter');
                $resident->fill($request->post());
                $request->session()->put('resident.reporter', $resident);

                $household = $request->session()->get('household.reporter');
                $household->fill($request->post());
                $request->session()->put('household.reporter', $household);
            }

            $request->session()->put('new_resident.reporter', true);
        } else {
            $request->session()->forget('resident.reporter');
            $request->session()->forget('new_resident.reporter');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('blotter.reporter', $request->resident_id);
        }

        return redirect('/blotters/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        if ($request->session()->get('resident.victim')) {
            $residentData = collect($request->session()->get('resident.victim'));
            $householdData = collect($request->session()->get('household.victim'));
            $residentData = json_decode($residentData->toBase()->merge($householdData)->toJson());
        } else if ($request->session()->get('blotter.victim')) {
            $residentData = DB::table('residents')
                ->join('households', 'households.id', '=', 'residents.household_id')
                ->join('religions', 'religions.id', '=', 'residents.religion_id')
                ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
                ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
                ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
                ->where('residents.archived', '=', '0')
                ->where('residents.id', '=', $request->session()->get('blotter.victim'))
                ->first();
        }

        return view('pages.admin.blotter.create.step_two', ['residentData' => $residentData ?? null]);
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
            if (empty($request->session()->get('resident.victim'))) {
                $resident = new Resident();
                $resident->fill($request->post());
                $request->session()->put('resident.victim', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household.victim', $household);
            } else {
                $resident = $request->session()->get('resident.victim');
                $resident->fill($request->post());
                $request->session()->put('resident.victim', $resident);

                $household = $request->session()->get('household.victim');
                $household->fill($request->post());
                $request->session()->put('household.victim', $household);
            }

            $request->session()->put('new_resident.victim', true);
        } else {
            $request->session()->forget('resident.victim');
            $request->session()->forget('new_resident.victim');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('blotter.victim', $request->resident_id);
        }

        return redirect('/blotters/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        if ($request->session()->get('resident.suspect')) {
            $residentData = collect($request->session()->get('resident.suspect'));
            $householdData = collect($request->session()->get('household.suspect'));
            $residentData = json_decode($residentData->toBase()->merge($householdData)->toJson());
        } else if ($request->session()->get('blotter.suspect')) {
            $residentData = DB::table('residents')
                ->join('households', 'households.id', '=', 'residents.household_id')
                ->join('religions', 'religions.id', '=', 'residents.religion_id')
                ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
                ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
                ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
                ->where('residents.archived', '=', '0')
                ->where('residents.id', '=', $request->session()->get('blotter.suspect'))
                ->first();
        }

        return view('pages.admin.blotter.create.step_three', ['residentData' => $residentData ?? null]);
    }

    public function post_StepThree(Request $request)
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
            if (empty($request->session()->get('resident.suspect'))) {
                $resident = new Resident();
                $resident->fill($request->post());
                $request->session()->put('resident.suspect', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household.suspect', $household);
            } else {
                $resident = $request->session()->get('resident.suspect');
                $resident->fill($request->post());
                $request->session()->put('resident.suspect', $resident);

                $household = $request->session()->get('household.suspect');
                $household->fill($request->post());
                $request->session()->put('household.suspect', $household);
            }

            $request->session()->put('new_resident.suspect', true);
        } else {
            $request->session()->forget('resident.suspect');
            $request->session()->forget('new_resident.suspect');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('blotter.suspect', $request->resident_id);
        }
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
        $blotter_id = $blotter->id;

        if ($request->session()->get('new_resident.reporter')) {
            $household = $request->session()->get('household.reporter');
            $household = Household::firstOrCreate($household->toArray());

            

            $resident = $request->session()->get('resident.reporter');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();

            $recipient = $resident->id;
            DB::table('blotter_recipients')->insert(['blotter_id' => $blotter_id, 'resident_id' => $recipient, 'blotter_role_id' => 1]);
            $request->session()->forget('blotter.reporter');
        } 

        if ($request->session()->get('new_resident.victim')) {
            $household = $request->session()->get('household.victim');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident.victim');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();
            
            $recipient = $resident->id;
            DB::table('blotter_recipients')->insert(['blotter_id' => $blotter_id, 'resident_id' => $recipient, 'blotter_role_id' => 2]);
            $request->session()->forget('blotter.victim');
        }

        if ($request->session()->get('new_resident.suspect')) {
            $household = $request->session()->get('household.suspect');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident.suspect');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();
            
            $recipient = $resident->id;
            DB::table('blotter_recipients')->insert(['blotter_id' => $blotter_id, 'resident_id' => $recipient, 'blotter_role_id' => 3]);
            $request->session()->get('blotter.suspect');
        }

        $recipients = [$request->session()->get('blotter.reporter'), $request->session()->get('blotter.victim'), $request->session()->get('blotter.suspect')];
        foreach ($recipients as $index => $recipient) {
            if ($recipient == null) continue;
            DB::table('blotter_recipients')->insert(['blotter_id' => $blotter_id, 'resident_id' => $recipient, 'blotter_role_id' => $index + 1]);
        }

        return redirect('/blotters/new/step-five');
    }

    public function create_StepFive(Request $request)
    {
        $request->session()->forget('blotter.reporter');
        $request->session()->forget('blotter.victim');
        $request->session()->forget('blotter.suspect');
        $request->session()->forget('new_resident.reporter');
        $request->session()->forget('new_resident.victim');
        $request->session()->forget('new_resident.suspect');
        addToLog('Create', 'Issued Blotter');
        return view('pages.admin.blotter.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blotter = DB::table('blotters')
            ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=', 'blotters.id')
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
            ->join('blotter_recipients', 'blotter_recipients.blotter_id', '=', 'blotters.id')
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

        addToLog('Update', "Updated Blotter ID: $id");

        return redirect("/blotters/$id");
    }
}
