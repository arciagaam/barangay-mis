<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SettingsController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());

        $civilStatus = DB::table('civil_status')->select('id', 'name')->orderBy('id', 'asc')->get();
        $occupation = DB::table('occupations')->select('id', 'name')->orderBy('id', 'asc')->get();
        $religion = DB::table('religions')->select('id', 'name')->orderBy('id', 'asc')->get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.maintenance.settings.index');
    }

    public function index_positions(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $positions = DB::table('official_positions')
            ->latest('official_positions.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $positions = DB::table('official_positions')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.positions.index', ['positions' => $positions]);
    }

    public function index_civil_status(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $civil_status = DB::table('civil_status')
            ->latest('civil_status.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $civil_status = DB::table('civil_status')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.civil_status.index', ['civil_status' => $civil_status]);
    }

    public function index_occupations(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $occupations = DB::table('occupations')
            ->latest('occupations.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $occupations = DB::table('occupations')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.occupations.index', ['occupations' => $occupations]);
    }

    public function index_religions(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $religions = DB::table('religions')
            ->latest('religions.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $religions = DB::table('religions')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.religions.index', ['religions' => $religions]);
    }

    public function index_security_questions(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $security_questions = DB::table('security_questions')
            ->latest('security_questions.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $security_questions = DB::table('security_questions')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.security_questions.index', ['security_questions' => $security_questions]);
    }

    public function index_sex(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $sex = DB::table('sex')
            ->latest('sex.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $sex = DB::table('sex')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.sex.index', ['sex' => $sex]);
    }

    public function index_archive(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $reasons = DB::table('archive_reasons')
            ->latest('archive_reasons.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $reasons = DB::table('archive_reasons')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.archive_reasons.index', ['reasons' => $reasons]);
    }

    public function index_streets(Request $request)
    {
        $rows = $request->rows;

        if($request->search || $request->search == '') {
            $streets = DB::table('streets')
            ->latest('streets.created_at')
            ->where(function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        } else {
            $streets = DB::table('streets')
            ->latest()
            ->paginate($rows ?? 10)
            ->appends(request()->query());
        }

        return view('pages.admin.maintenance.settings.streets.index', ['streets' => $streets]);
    }
}
