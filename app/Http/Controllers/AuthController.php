<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{

    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
    }

    public function index()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors(['invalid' => 'Invalid Credentials'])->onlyInput('username');

    }

    public function logout(Request $request) : RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
