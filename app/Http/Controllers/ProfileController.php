<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{

    protected $roles;

    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
        
        $this->roles = DB::table('roles')
        ->orderBy('id')
        ->get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = DB::table("users")
        ->join('roles', 'roles.id', 'users.role_id')
        ->select([
            'users.*',
            'roles.name as role'
        ])
        ->where('users.id', auth()->user()->id)
        ->first('users.created_at');

        return view('pages.admin.profile.index', ['user' => $user, 'editing' => false, 'roles' => $this->roles]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = DB::table("users")
        ->join('roles', 'roles.id', 'users.role_id')
        ->select([
            'users.*',
            'roles.name as role'
        ])
        ->where('users.id', auth()->user()->id)
        ->first('users.created_at');

        return view('pages.admin.profile.index', ['user' => $user ,'editing' => true, 'roles' => $this->roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $formFields = $request->validate([
            'first_name' => 'required',
            'middle_name' => '',
            'last_name' => 'required',
            'role_id' => 'required',
        ]);

        DB::table('users')
        ->where('id', auth()->user()->id)
        ->update($formFields);

        $id = auth()->user()->id;
        addToLog('Update', "User ID: $id Updated");

        return redirect('/profile');
    }

    public function changePassword(Request $request)
    {
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect('/profile')->with('error', 'Incorrect password. Please try again.');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required',
            'confirm_password' => ['required', 'same:password'],
        ]);
    
        if ($validator->fails()) {
            return redirect('/profile')->with('error', 'Incorrect yes. Please try again.');
        }

        DB::table('users')
        ->where('id', '=', auth()->user()->id)
        ->update(['password' => bcrypt($request->password)]);

        $id = auth()->user()->id;
        addToLog('Change Password', "Password for User ID: $id Changed");


        return redirect('/profile')->with('success', 'Password updated successfully!');
    }
}
