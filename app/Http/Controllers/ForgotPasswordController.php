<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
    }

    public function index()
    {
        return view('pages.auth.forgot_password.index');
    }

    public function usernameCheck(Request $request)
    {
        $request->validate([
            'username' => 'required'
        ]);

        $user = DB::table('users')
        ->where('username', $request->username)
        ->first();

        if(!$user) {
            return back()->with('error', 'Username not found');
        }else{
            $request->session()->put('user_id',$user->id);
        }

        return redirect('/forgot-password/security-question');
    }

    public function securityCheck(Request $request)
    {
        $securityQuestion = DB::table('users')
        ->join('security_questions', 'security_questions.id', 'users.security_question_id')
        ->where('users.id', $request->session()->get('user_id'))
        ->select('security_questions.name as security_question')
        ->first();

        return view('pages.auth.forgot_password.security_check', ['securityQuestion' => $securityQuestion->security_question]);
    }

    public function questionCheck(Request $request)
    {
        $request->validate([
            'security_answer' => 'required'
        ]);

        $user = DB::table('users')
        ->join('security_questions', 'security_questions.id', 'users.security_question_id')
        ->where('users.id', $request->session()->get('user_id'))
        ->where('users.security_question_answer', $request->security_answer)
        ->first();

        if(!$user) {
            return back()->with('error', 'Invalid security question answer');
        }

        return redirect('/forgot-password/change-password');
    }

    public function passwordCheck(Request $request)
    {
        return view('pages.auth.forgot_password.password_check');
        
    }

    public function changePassword(Request $request)
    {
        $formFields = $request->validate([
            'password' => 'required',
            'confirm_password' => ['required', 'same:password'],
        ]);

        DB::table('users')
        ->where('id', $request->session()->get('user_id'))
        ->update(['password' => bcrypt($request->password)]);

        addToLog('Change Password', "User ID: " . $request->session()->get('user_id') . " changed password");

        $request->session()->forget('user_id');
        return view('pages.auth.forgot_password.success');
    }
}
