<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Resident;
use App\Models\Residents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public $options;

    public function __construct()
    {
        $civilStatus = DB::table('civil_status')->select('id', 'name')->orderBy('id', 'asc')->get();
        $occupation = DB::table('occupations')->select('id', 'name')->orderBy('id', 'asc')->get();
        $religion = DB::table('religions')->select('id', 'name')->orderBy('id', 'asc')->get();

        $this->options = ['civilStatus' => $civilStatus, 'occupation' => $occupation, 'religion' => $religion];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->forget('resident');
        $request->session()->forget('household');

        $rows = $request->rows;

        if($request->search || $request->search != ''){
            $residents = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
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
            ->orderBy('residents.created_at', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $residents = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id')
            ->where('residents.archived', '=', '0')
            ->orderBy('residents.created_at', 'asc')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }


        return view('pages.admin.residents_information.index', ['residents' => $residents, 'rows' => $rows]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne()
    {
        return view('pages.admin.residents_information.create.step_one', ['options' => $this->options]);
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
            'place_of_birth' => 'required',
            'civil_status_id' => 'required',
            'occupation_id' => 'required',
            'religion_id' => 'required',
        ]);

        if (empty($request->session()->get('resident'))) {
            $resident = new Resident();
            $resident->fill($formFields);
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
                'disabled' => '',
            ]);
        } else {
            $formFields = $request->validate([
                'voter_status' => 'required',
                'precinct_number' => '',
                'disabled' => '',
            ]);
        }

        $formFields['disabled'] = $request->disabled == 'on' ? 1 : 0;

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
        ->where('house_number', '=', $household->block)
        ->where('others', '=', $household->address_others)
        ->first();

        if (isset($householdDb)) {
            $resident->fill(['household_id' => $householdDb->id]);
        } else {
            $household->save();
            $resident->fill(['household_id' => $household->id]);
        }

        $resident->save();

        $request->session()->forget('citizen');
        $request->session()->forget('household');

        return view('pages.admin.residents_information.create.complete');
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
        $resident = DB::table('residents')
        ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
        ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
        ->join('religions', 'religions.id', '=', 'residents.religion_id')
        ->join('households', 'households.id', '=', 'residents.household_id')
        ->where('residents.id', '=', $id)
        ->select('residents.*', 'residents.id as resident_id', 'civil_status.name as civil_status', 'occupations.name as occupation', 'religions.name as religion', 'households.*', 'households.id as household_id')
        ->first();

        return view('pages.admin.residents_information.show', ['resident' => $resident, 'options' => $this->options, 'editing' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resident = DB::table('residents')
        ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
        ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
        ->join('religions', 'religions.id', '=', 'residents.religion_id')
        ->join('households', 'households.id', '=', 'residents.household_id')
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

        return redirect("/residents/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function archive(string $id)
    {
        DB::table('residents')
        ->where('id', '=', $id)
        ->update(['archived' => 1]);

        return back();
    }
}
