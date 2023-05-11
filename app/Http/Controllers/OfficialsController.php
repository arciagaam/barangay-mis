<?php

namespace App\Http\Controllers;

use App\Models\Official;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use PhpParser\Node\Stmt\Return_;

use function Ramsey\Uuid\v1;

class OfficialsController extends Controller
{
    protected $positions;

    public function __construct()
    {
        $this->positions = DB::table('official_positions')
        ->latest()
        ->get();
        View::share('barangayInformation', DB::table('barangay_information')->first());
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

        return view('pages.admin.officials.create.step_one', ['residentData' => $residentData ?? null, 'resident' => $official->resident_id ?? null]);

    }

    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'resident_id' => 'required'
        ]);

        
        if (empty($request->session()->get('official'))) {
            $official = new Official();
            $official->fill($formFields);
            $request->session()->put('official', $official);
        } else {
            $official = $request->session()->get('official');
            $official->fill($formFields);
            $request->session()->put('official', $official);
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
        $official = $request->session()->get('official');
        $official->save();

        $request->session()->forget('official');
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
        //
    }
}
