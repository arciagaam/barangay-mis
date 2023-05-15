<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CertificateController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());

        $this->middleware(function ($request, $next) {
            if (str_contains($request->path(), 'certificates/new') && !str_contains($request->path(), 'certificates/new/step-one') && $request->session()->missing('certificate')) {
                return redirect('/certificates/new/step-one');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $request->session()->forget('certificate');

        $rows = $request->rows;

        if($request->search || $request->search != ''){
            $certificates = DB::table('certificates')
            ->join('residents', 'residents.id', '=', 'certificates.resident_id')
            ->join('certificate_types', 'certificate_types.id', '=', 'certificates.certificate_type_id')
            ->where(function($query) use ($request) {
                $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', '%' . $request->search . '%')
                ->orWhere('first_name', 'like', '%'.$request->search.'%')
                ->orWhere('middle_name', 'like', '%'.$request->search.'%')
                ->orWhere('last_name', 'like', '%'.$request->search.'%')
                ->orWhere('nickname', 'like', '%'.$request->search.'%')
                ->orWhere('certificate_types.name', 'like', '%'.$request->search.'%')
                ->orWhere('certificates.created_at', 'like', '%'.$request->search.'%');
            })
            ->orderBy('certificates.created_at', 'asc')
            ->select('residents.first_name', 'residents.middle_name', 'residents.last_name', 'certificates.*', 'certificate_types.name as certificate_type')
            ->paginate($rows ?? 10)
            ->appends(request()->query());

        }else{
            $certificates = DB::table('certificates')
            ->join('residents', 'residents.id', '=', 'certificates.resident_id')
            ->join('certificate_types', 'certificate_types.id', '=', 'certificates.certificate_type_id')
            ->orderBy('certificates.created_at', 'asc')
            ->select('residents.first_name', 'residents.middle_name', 'residents.last_name', 'certificates.*', 'certificate_types.name as certificate_type')
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }
        
        return view('pages.admin.certificate.index', ['certificates' => $certificates]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne()
    {
        $certificate_types = DB::table('certificate_types')
        ->orderBy('id', 'asc')
        ->get();

        return view('pages.admin.certificate.create.step_one', ['certificate_types' => $certificate_types]);
    }

    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'certificate_type_id' => 'required'
        ]);

        if (empty($request->session()->get('certificate'))) {
            $certificate = new Certificate();
            $certificate->fill($formFields);
            $request->session()->put('certificate', $certificate);
        } else {
            $certificate = $request->session()->get('certificate');
            $certificate->fill($formFields);
            $request->session()->put('certificate', $certificate);
        }

        return redirect('/certificates/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        $certificate = $request->session()->get('certificate');

        if ($certificate->resident_id) {
            $residentData = DB::table('residents')
            ->join('households', 'households.id', '=', 'residents.household_id')
            ->join('religions', 'religions.id', '=', 'residents.religion_id')
            ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
            ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
            ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
            ->where('residents.archived', '=', '0')
            ->where('residents.id', '=', $certificate->resident_id)
            ->first();
        }


        return view('pages.admin.certificate.create.step_two', ['residentData' => $residentData ?? null, 'resident' => $certificate->resident_id ?? null]);
    }

    public function post_StepTwo(Request $request)
    {
        $formFields = $request->validate([
            'resident_id' => 'required'
        ]);

        $certificate = $request->session()->get('certificate');
        $certificate->fill(['resident_id' => $request->resident_id]);
        $request->session()->put('certificate', $certificate);

        return redirect('/certificates/new/step-three');
    }

    public function create_StepThree(Request $request)
    {
        $certificate = $request->session()->get('certificate');
        $certificate->save();
        
        return view('pages.admin.certificate.create.step_three', ['certificate' => $certificate]);
    }

    public function post_StepThree(Request $request)
    {
        $certificate = $request->session()->get('certificate');
        $type_id = $certificate->certificate_type_id;
        $certificate->save();

        $request->session()->put('certificate_id', $certificate->id);
        $request->session()->put('certificate_type_id', $type_id);

        // BUSINESS PERMIT
        if($type_id == 1) {
            $formFields = $request->validate([
                'business_name' => 'required',
                'location' => 'required',
                'operator' => 'required',
                'address' => 'required',
                'complying' => '',
                'partially_complying' => '',
                'no_objection' => '',
                'recommends' => '',
            ]);

        } else if ($type_id == 2) {
            $formFields = $request->validate([
                'purpose' => 'required',
            ]);
        } else if ($type_id == 3) {
            $formFields = $request->validate([
                'purpose' => 'required',
            ]);
        }

        foreach($request->post() as $key => $inputField) {
            if ($key == '_token') {
                continue;
            }
            DB::table('certificate_data')
            ->insert(['certificate_id' => $certificate->id,'certificate_type_id' => $type_id, 'certificate_input_id' => $key, 'value' => $inputField]);
        }

        return redirect('/certificates/new/step-four');
    }

    public function create_StepFour(Request $request)
    {
        $certificateId = $request->session()->get('certificate_id');
        $certificateTypeId = $request->session()->get('certificate_type_id');
        addToLog('Create', 'Issued Certificate');
        return view('pages.admin.certificate.create.complete', ['certificateId' => $certificateId, 'certificateTypeId' => $certificateTypeId]);
    }

    public function print(Request $request)
    {
        $resident = DB::table('certificates')
        ->join('residents', 'residents.id', '=', 'certificates.resident_id')
        ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
        ->join('households', 'households.id', '=', 'residents.household_id')
        ->where('certificates.id', '=', $request->certificate_id)
        ->select([
            'certificates.created_at',
            'residents.first_name',
            'residents.middle_name',
            'residents.last_name',
            'civil_status.name as civil_status',
            'households.house_number',
            'households.purok',
            'households.block',
            'households.lot',
            'households.others',
            'households.subdivision',
        ])
        ->first();

        $fields = DB::table('certificate_data')
        ->where('certificate_id', '=', $request->certificate_id)
        ->select(['certificate_input_id as input_id', 'value'])
        ->get();

        if($request->certificate_type_id == 1) {
            $view = 'pages.certificates.business_permit';
        } else if($request->certificate_type_id == 2) {
            $view = 'pages.certificates.barangay_clearance';
        } else if($request->certificate_type_id == 3) {
            $view = 'pages.certificates.indigency';
        }

        $request->session()->forget('certificate');
        $request->session()->forget('certificate_type_id');
        addToLog('Print', "Printed Certificate ID: $request->certificate_id");
        return view($view, ['resident' => $resident, 'fields' => $fields]);
    }

}
