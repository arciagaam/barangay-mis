<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class UserController extends Controller
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
        $rows = $request->rows;

        if($request->search || $request->search != '') {
            $users = DB::table('users') 
                ->join('roles', 'roles.id', 'users.role_id')
                ->latest('users.id')
                ->select([
                    'users.*',
                    'roles.name as role'
                ])
                ->where(function($query) use ($request) {
                    $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
                    ->orWhere('first_name', 'like', $request->search.'%')
                    ->orWhere('middle_name', 'like', $request->search.'%')
                    ->orWhere('last_name', 'like', $request->search.'%')
                    ->orWhere('username', 'like', $request->search.'%')
                    ->orWhere('roles.name', 'like', $request->search.'%');
                })
                ->paginate($rows ?? 10)
                ->appends(request()->query());
        } else {
            $users = DB::table('users')
                ->join('roles', 'roles.id', 'users.role_id')
                ->latest('users.id')
                ->select([
                    'users.*',
                    'roles.name as role'
                ])
                ->paginate($rows ?? 10)
                ->appends(request()->query());
        }

        return view('pages.admin.maintenance.user.index', ['users' => $users, ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_StepOne()
    {
        $questions = DB::table('security_questions')
        ->latest()
        ->get();

        $roles = DB::table('roles')
            ->latest()
            ->get();

        return view('pages.admin.maintenance.user.create.step_one', ['roles' => $roles, 'questions' => $questions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function post_StepOne(Request $request)
    {
        $formFields = $request->validate([
            'first_name' => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'username' => "required|unique:users,username",
            'password' => 'required',
            'role_id' => 'required',
            'security_question_id' => 'required',
            'security_question_answer' => 'required',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        DB::table('users')
        ->insert($formFields);

        return redirect('/maintenance/users/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        addToLog('Create', "New User Created");

        return view('pages.admin.maintenance.user.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = DB::table('users')
            ->join('roles', 'roles.id', 'users.role_id')
            ->select('users.*', 'roles.name as role')
            ->where('users.id', $id)
            ->first();

        $securityQuestions = DB::table('security_questions')->get();

        return view('pages.admin.maintenance.user.show', ['user' => $user, 'editing' => false, 'securityQuestions' => $securityQuestions]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->first();

        $roles = DB::table('roles')
            ->latest()
            ->get();

        $securityQuestions = DB::table('security_questions')->get();

        return view('pages.admin.maintenance.user.show', ['user' => $user, 'roles' => $roles, 'editing' => true, 'securityQuestions' => $securityQuestions]);
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
            'password' => 'required',
            'role_id' => 'required',
            'username' => "required|unique:users,username, $id",
            'security_question_id' => 'required',
            'security_question_answer' => 'required',
        ]);

        $formFields['password'] = bcrypt($request->password);

        DB::table('users')
        ->where('id', $id)
        ->update($formFields);

        addToLog('Update', "User ID: $id Updated");

        return redirect("/maintenance/users/$id");
    }

    public function destroy(Request $request, $id)
    {
        DB::table('users')
        ->delete($id);
        addToLog('Delete', "User ID: $request->id Deleted");
    }
}
