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
        $roles = DB::table('roles')
            ->latest()
            ->get();

        return view('pages.admin.maintenance.user.create.step_one', ['roles' => $roles]);
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
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        DB::table('users')
            ->insert($formFields);

        return redirect('/maintenance/users/new/step-two');
    }

    public function create_StepTwo(Request $request)
    {
        return view('pages.admin.maintenance.user.create.complete');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = DB::table('users')
            ->where('id', $id)
            ->first();

        return view('pages.admin.maintenance.user.show', ['user' => $user, 'editing' => false]);
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

        return view('pages.admin.maintenance.user.show', ['user' => $user, 'roles' => $roles, 'editing' => true]);
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
        ]);

        DB::table('users')
        ->where('id', $id)
        ->update($formFields);

        return redirect("/maintenance/users/$id");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
