<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use App\Models\Residents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ResidentController extends Controller
{
    public $options;

    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
        View::share('reasons',  DB::table('archive_reasons')->latest()->get());

        $civilStatus = DB::table('civil_status')->select('id', 'name')->orderBy('id', 'asc')->get();
        $occupation = DB::table('occupations')->select('id', 'name')->orderBy('id', 'asc')->get();
        $religion = DB::table('religions')->select('id', 'name')->orderBy('id', 'asc')->get();
        $genders = DB::table('genders')->select('id', 'name')->orderBy('id', 'asc')->get();

        $this->options = ['civilStatus' => $civilStatus, 'occupation' => $occupation, 'religion' => $religion, 'genders' => $genders];

        $this->middleware(function ($request, $next) {
            if (str_contains($request->path(), 'residents/new') && !str_contains($request->path(), 'residents/new/step-one') && $request->session()->missing('resident')) {
                return redirect('/residents/new/step-one');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->forget('resident');
        $request->session()->forget('household');

        $rows = $request->rows;

        $filter = $request->filter ?? null;

        if($request->search || $request->search != ''){
            $residents = DB::table('residents')
            ->leftJoin('households', 'households.id', '=', 'residents.household_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id')
            ->where('residents.archived', '=', '0')
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', '%' . $request->search . '%')
                ->orWhere('first_name', 'like', '%'.$request->search.'%')
                ->orWhere('middle_name', 'like', '%'.$request->search.'%')
                ->orWhere('last_name', 'like', '%'.$request->search.'%')
                ->orWhere('nickname', 'like', '%'.$request->search.'%')
                ->orWhere('birth_date', 'like', '%'.$request->search.'%')
                ->orWhere('place_of_birth', 'like', '%'.$request->search.'%')
                ->orWhere('house_number', 'like', '%'.$request->search.'%')
                ->orWhere('purok', 'like', '%'.$request->search.'%')
                ->orWhere('block', 'like', '%'.$request->search.'%')
                ->orWhere('lot', 'like', '%'.$request->search.'%')
                ->orWhere('others', 'like', '%'.$request->search.'%')
                ->orWhere('subdivision', 'like', '%'.$request->search.'%');
            })
            ->when($filter != null, function ($query) use ($request, $filter) {
                switch(lcfirst($filter)) {
                    case 'male' : $query->where("residents.sex", 1); break;
                    case 'female' : $query->where("residents.sex", 2); break;
                    case 'voter' : $query->where("residents.voter_status", 1); break;
                    case 'non-voter' : $query->where("residents.voter_status", 0); break;
                    case 'toddler' : $query->where("residents.age",'<=', 3); break;
                    case 'senior' : $query->where("residents.age", '>=', 60); break;
                }
            })
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $residents = DB::table('residents')
            ->when($filter != null, function ($query) use ($request, $filter) {
                switch(lcfirst($filter)) {
                    case 'male' : $query->where("residents.sex", 1); break;
                    case 'female' : $query->where("residents.sex", 2); break;
                    case 'voter' : $query->where("residents.voter_status", 1); break;
                    case 'non-voter' : $query->where("residents.voter_status", 0); break;
                    case 'toddler' : $query->where("residents.age",'<=', 3); break;
                    case 'senior' : $query->where("residents.age", '>=', 60); break;
                }
            })
            ->leftJoin('households', 'households.id', '=', 'residents.household_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id')
            ->where('residents.archived', '=', '0')
            ->orderBy('residents.created_at', 'desc')
            ->orderBy('residents.id', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.residents_information.index', ['residents' => $residents, 'rows' => $rows]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne(Request $request)
    {
        $resident = $request->session()->get('resident');
        return view('pages.admin.residents_information.create.step_one', ['options' => $this->options, 'resident' => $resident]);
    }

    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'first_name'  => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'nickname' => '',
            'sex' => 'required',
            'gender_id' => 'nullable',
            'birth_date' => 'required',
            'age' => 'required',
            'place_of_birth' => 'required',
            'civil_status_id' => 'required',
            'occupation_id' => 'required',
            'religion_id' => 'required',
        ]);

        // check if may resident session
        if (empty($request->session()->get('resident'))) {
            $resident = new Resident();
            $resident->fill($formFields);
            // nilalagay yung inputfields or yung resident sa isang session
            $request->session()->put('resident', $resident);
        } else {
            $resident = $request->session()->get('resident');
            $resident->fill($formFields);
            $request->session()->put('resident', $resident);
        }

        return redirect('/residents/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        $resident = $request->session()->get('resident');
        $household = $request->session()->get('household');

        return view('pages.admin.residents_information.create.step_two', ['resident' => $resident, 'household' => $household]);
    }

    public function post_StepTwo(Request $request)
    {
        $formFields = $request->validate([
            'phone_number' => ['numeric', 'required'],
            'telephone_number' => 'nullable',
            'block' => 'nullable',
            'lot' => 'nullable',
            'others' => 'nullable',
            'subdivision' => 'nullable',
            'purok' => 'nullable',
            'house_number' => 'required'
        ]);

        $residentFields = [
            'phone_number' => $formFields['phone_number'],
            'telephone_number' => $formFields['telephone_number'],
        ];

        $householdFields = [
            'block' => $formFields['block'],
            'lot' => $formFields['lot'],
            'others' => $formFields['others'],
            'subdivision' => $formFields['subdivision'],
            'purok' => $formFields['purok'],
            'house_number' => $formFields['house_number'],
        ];

        $resident = $request->session()->get('resident');
        $resident->fill($residentFields);
        $request->session()->put('resident', $resident);

        if (empty($request->session()->get('household'))) {
            $household = new Household();
            $household->fill($householdFields);
            $request->session()->put('household', $household);
        } else {
            $household = $request->session()->get('household');
            $household->fill($householdFields);
            $request->session()->put('household', $household);
        }

        return redirect('/residents/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        $resident = $request->session()->get('resident');
        $household = $request->session()->get('household');

        return view('pages.admin.residents_information.create.step_three', ['resident' => $resident, 'housholed' => $household]);
    }

    public function post_StepThree(Request $request)
    {
        if ($request->voter_status == 1) {
            $formFields = $request->validate([
                'voter_status' => 'required',
                'precinct_number' => 'required',
                'single_parent' => 'nullable',
                'disabled' => 'nullable',
            ]);
        } else {
            $formFields = $request->validate([
                'voter_status' => 'required',
                'precinct_number' => 'nullable',
                'single_parent' => 'nullable',
                'disabled' => 'nullable',
            ]);
        }

        $formFields['disabled'] = $request->disabled == 'on' ? 1 : 0;
        $formFields['single_parent'] = $request->single_parent == 'on' ? 1 : 0;

        $resident = $request->session()->get('resident');
        $resident->fill($formFields);
        $request->session()->put('resident', $resident);


        return redirect('/residents/new/step-four');
    }

    public function create_StepFour(Request $request)
    {
        $household = $request->session()->get('household');
        $resident = $request->session()->get('resident');

        if (!isset($household) && !isset($resident)) {
            redirect('/residents/new/step-one');
        }

        $householdDb = DB::table('households')            
        ->where('house_number', '=', $household->house_number)
        ->where('others', '=', $household->address_others)
        ->first();

        if (isset($householdDb)) {
            $resident->fill(['household_id' => $householdDb->id]);
        } else {
            $household->save();
            $resident->fill(['household_id' => $household->id]);
        }

        $resident->save();
        addToLog('Create', 'Resident Created');

        $request->session()->forget('citizen');
        $request->session()->forget('household');

        return view('pages.admin.residents_information.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resident = DB::table('residents')
        ->leftJoin('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
        ->leftJoin('genders', 'genders.id', '=', 'residents.gender_id')
        ->leftJoin('occupations', 'occupations.id', '=', 'residents.occupation_id')
        ->leftJoin('religions', 'religions.id', '=', 'residents.religion_id')
        ->leftJoin('households', 'households.id', '=', 'residents.household_id')
        ->where('residents.id', '=', $id)
        ->select('residents.*', 'residents.id as resident_id','genders.name as gender', 'civil_status.name as civil_status', 'occupations.name as occupation', 'religions.name as religion', 'households.*', 'households.id as household_id')
        ->first();

        return view('pages.admin.residents_information.show', ['resident' => $resident, 'options' => $this->options, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resident = DB::table('residents')
        ->leftJoin('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
        ->leftJoin('occupations', 'occupations.id', '=', 'residents.occupation_id')
        ->leftJoin('religions', 'religions.id', '=', 'residents.religion_id')
        ->leftJoin('households', 'households.id', '=', 'residents.household_id')
        ->where('residents.id', '=', $id)
        ->select('residents.*', 'residents.id as resident_id','civil_status.name as civil_status', 'occupations.name as occupation', 'religions.name as religion', 'households.*', 'households.id as household_id')
        ->first();

        return view('pages.admin.residents_information.show', ['resident' => $resident, 'options' => $this->options, 'editing' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'first_name' => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'nickname' => 'required',
            'sex' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'place_of_birth' => 'required',
            'civil_status_id' => 'required',
            'occupation_id' => 'required',
            'religion_id' => 'required',
            'house_number' => 'required',
            'purok' => '',
            'block' => '',
            'lot' => '',
            'others' => '',
            'subdivision' => '',
            'voter_status' => 'required',
            'precinct_number' => '',
            'disabled' => 'required',
        ]);

        $residentFields = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'nickname' => $request->nickname,
            'sex' => $request->sex,
            'birth_date' => $request->birth_date,
            'age' => $request->age,
            'place_of_birth' => $request->place_of_birth,
            'civil_status_id' => $request->civil_status_id,
            'occupation_id' => $request->occupation_id,
            'religion_id' => $request->religion_id,
            'voter_status' => $request->voter_status,
            'precinct_number' => $request->precinct_number,
            'disabled' => $request->disabled
        ];

        $householdFields = [
            'house_number' => $request->house_number,
            'purok' => $request->purok,
            'block' => $request->block,
            'lot' => $request->lot,
            'others' => $request->others,
            'subdivision' => $request->subdivision
        ];

        $householdID = DB::table('residents')
        ->where('id', '=', $id)
        ->first()->household_id;

        DB::table('residents')
        ->where('id', '=', $id)
        ->update($residentFields);

        DB::table('households')
        ->where('id', '=', $householdID)
        ->update($householdFields);

        addToLog('Update', "Resident ID: $id Updated");

        return redirect("/residents/$id");
    }


    public function archive(string $id, Request $request)
    {
        DB::table('residents')
        ->where('id', '=', $id)
        ->update(['archived' => 1, 'archive_reason_id' => $request->reason]);
        addToLog('Archive', "Resident ID: $id Archived");

        return back();
    }

    public function recover(string $id)
    {
        DB::table('residents')
        ->where('id', '=', $id)
        ->update(['archived' => 0]);

        addToLog('Recover', "Resident ID: $id Recovered");

        return back();
    }
}
