<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Official;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class OfficialsController extends Controller
{
    protected $positions;

    public function __construct()
    {
        $this->positions = DB::table('official_positions')
        ->latest()
        ->get();
        View::share('barangayInformation', DB::table('barangay_information')->first());

        $this->middleware(function ($request, $next) {
            if (str_contains($request->path(), 'officials/new') && !str_contains($request->path(), 'officials/new/step-one') && ($request->session()->missing('resident') && $request->session()->missing('official'))) {
                return redirect('/officials/new/step-one');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->session()->forget('official');
        
        $rows = $request->rows;
        $years = DB::table('officials')
        ->select(DB::raw('YEAR(term_start) as year'))
        ->distinct('year')
        ->orderBy('year', 'asc')
        ->get();

        $selectedYear = null;

        if (count($years) > 0) {
            if (!$request->year) {

                $array_years = array();
                foreach ($years as $year) {
                    array_push($array_years, $year->year);
                }

                $currentYear = date("Y");

                if (in_array($currentYear, $array_years)) {
                    $selectedYear = $currentYear;
                } else {
                    foreach ($array_years as $year) {
                        if ($currentYear > $year) {
                            $selectedYear = $year;
                        }
                    }
                }

                if(!$selectedYear) {
                    $selectedYear = $array_years[count($array_years)-1];
                }

                return redirect('/officials?year=' . $selectedYear);
            } else {
                $selectedYear = $request->year;
            }
        } else {
            $selectedYear = date("Y");

            $years = [(object)["year" => $selectedYear]];
        }


        if($request->search || $request->search != '') {
            $officials = DB::table('officials')
            ->join('residents', 'residents.id', 'officials.resident_id')
            ->join('official_positions', 'official_positions.id', 'officials.position_id')
            ->where(DB::raw('YEAR(officials.term_start)'), '=', $selectedYear)
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                ->orWhere('first_name', 'like', $request->search . '%')
                ->orWhere('middle_name', 'like', $request->search . '%')
                ->orWhere('last_name', 'like', $request->search . '%')
                ->orWhere('term_start', 'like', $request->search . '%')
                ->orWhere('term_end', 'like', $request->search . '%')
                ->orWhere('official_positions.name', 'like', $request->search . '%');
            })
            ->select([
                'residents.first_name',
                'residents.middle_name',
                'residents.nickname',
                'residents.last_name',
                'official_positions.name as position',
                'officials.id',
                'officials.term_start',
                'officials.term_end',
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());

        } else {
            $officials = DB::table('officials')
            ->join('residents', 'residents.id', 'officials.resident_id')
            ->join('official_positions', 'official_positions.id', 'officials.position_id')
            ->where(DB::raw('YEAR(officials.term_start)'), '=', $selectedYear)
            ->select([
                'residents.first_name',
                'residents.middle_name',
                'residents.nickname',
                'residents.last_name',
                'official_positions.name as position',
                'officials.id',
                'officials.term_start',
                'officials.term_end',
            ])
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.officials.index', ['officials' => $officials, 'years' => $years, 'selectedYear' => $selectedYear]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne(Request $request)
    {
        $official = $request->session()->get('official');
        $religions = DB::table('religions')->orderBy('id')->get();


        if ($official && $official->resident_id) {
            $residentData = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->join('religions', 'religions.id', '=', 'residents.religion_id')
            ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
            ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
            ->where('residents.archived', '=', '0')
            ->where('residents.id', '=', $official->resident_id)
            ->first();
        }

        return view('pages.admin.officials.create.step_one', ['residentData' => $residentData ?? null, 'resident' => $official->resident_id ?? null, 'official' => $official, 'religions' => $religions]);

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
            'occupation' => 'required',
            'religion_id' => 'required',
            'house_number' => 'required',
            'purok' => '',
            'block' => '',
            'lot' => '',
            'others' => '',
            'subdivision' => '',
            'voter_status' => '',
            'precinct_number' => '',
            'disabled' => '',
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
            ->where('residents.place_of_birth', $request->place_of_birth)
            ->where('residents.occupation_id', $request->occupation_id)
            ->where('residents.religion_id', $request->religion_id)
            ->where('residents.voter_status', $request->voter_status)
            ->where('residents.precinct_number', $request->precinct_number)
            ->where('residents.disabled', $request->disabled)
            ->where('households.house_number', $request->house_number)
            ->where('households.purok', $request->purok)
            ->where('households.block', $request->block)
            ->where('households.lot', $request->lot)
            ->where('households.others', $request->others)
            ->where('households.subdivision', $request->subdivision)
            ->first();

        if (!$checkResident) {
            $formFields['voter_status'] = lcfirst($request->voter_status) == 'registered' ? 1 : 0; 
            $formFields['disabled'] = lcfirst($request->disabled) == 'abled' ? 1 : 0; 

            if (empty($request->session()->get('resident'))) {
                $resident = new Resident();
                $resident->fill($formFields);
                $request->session()->put('resident', $resident);

                $household = new Household();
                $household->fill($formFields);
                $request->session()->put('household', $household);
            } else {
                $resident = $request->session()->get('resident');
                $resident->fill($request->post());
                $request->session()->put('resident', $resident);

                $household = $request->session()->get('household');
                $household->fill($request->post());
                $request->session()->put('household', $household);
            }
            
            if(empty($request->session()->get('official'))) {
                $official = new Official();
                $request->session()->put('official', $official);
            }

            $request->session()->put('new_resident', true);
        } else {
            $request->session()->forget('resident');
            $request->session()->forget('household');
            $request->session()->forget('new_resident');

            if (!DB::table('residents')->where('id', '=', $request->resident_id)->exists()) {
                return back()->with('error', 'Resident not found');
            }

            $request->session()->put('official', $request->resident_id);
        }


        return redirect('officials/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        $official = $request->session()->get('official');

        return view('pages.admin.officials.create.step_two', ['positions' => $this->positions, 'official' => $official]);
    }

    public function post_StepTwo(Request $request)
    {
        $formFields = $request->validate([
            'position_id' => 'required',
            'term_start' => 'required',
            'term_end' => 'required',
        ]);

        $official = $request->session()->get('official');
        $official->fill($formFields);
        $request->session()->put('official', $official);

        return redirect('officials/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        if ($request->session()->get('new_resident')) {

            $household = $request->session()->get('household');
            $household = Household::firstOrCreate($household->toArray());

            $resident = $request->session()->get('resident');
            $resident->fill(['household_id' => $household->id]);
            $resident->save();

            $recipient = $resident->id;

            $official = $request->session()->get('official');
            $official->fill(['resident_id' => $recipient]);
            $official->save();

            addToLog('Create', "New Household Created");
            addToLog('Create', "New Resident Created");
            
            $request->session()->forget('resident');
            $request->session()->forget('household');
            $request->session()->forget('new_resident');
            
        } else {
            $official = $request->session()->get('official');
            $official->save();
        }

        $request->session()->forget('official');

        addToLog('Create', "New Official Created");

        return view('pages.admin.officials.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $official = DB::table('officials')
        ->join('residents', 'residents.id', 'officials.resident_id')
        ->join('official_positions', 'official_positions.id', 'officials.position_id')
        ->where('officials.id', $id)
        ->select([
            'residents.id as resident_id',
            'residents.first_name',
            'residents.middle_name',
            'residents.nickname',
            'residents.last_name',
            'residents.sex',
            'residents.birth_date',
            'residents.age',
            'official_positions.name as position',
            'official_positions.id as position_id',
            'officials.id',
            'officials.term_start',
            'officials.term_end',
        ])
        ->first();

        return view('pages.admin.officials.show', ['official' => $official, 'editing' => false, 'positions' => $this->positions]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $official = DB::table('officials')
        ->join('residents', 'residents.id', 'officials.resident_id')
        ->join('official_positions', 'official_positions.id', 'officials.position_id')
        ->where('officials.id', $id)
        ->select([
            'residents.id as resident_id',
            'residents.first_name',
            'residents.middle_name',
            'residents.nickname',
            'residents.last_name',
            'residents.sex',
            'residents.birth_date',
            'residents.age',
            'official_positions.name as position',
            'official_positions.id as position_id',
            'officials.id',
            'officials.term_start',
            'officials.term_end',
        ])
        ->first();

        return view('pages.admin.officials.show', ['official' => $official, 'editing' => true, 'positions' => $this->positions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'position_id' => 'required',
            'term_start' => 'required',
            'term_end' => 'required',
        ]);

        DB::table('officials')
        ->where('id', $id)
        ->update($formFields);
        addToLog('Update', "Official ID: $id Updated");

        return redirect("officials/$id");
    }
}
