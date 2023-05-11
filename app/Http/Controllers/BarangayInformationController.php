<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BarangayInformationController extends Controller
{
    public function __construct()
    {
        View::share('barangayInformation', DB::table('barangay_information')->first());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.maintenance.barangay_information.index', ['editing' => false]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('pages.admin.maintenance.barangay_information.index', ['editing' => true]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'phone_number' => '',
            'email_address' => '',
            'logo_input' => 'nullable|image|max:4096',
        ]);
        
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('images/logo', 'public');
            $path = $request->file('logo')->store('temp');
            $file = $request->file('logo');
            $fileName = $formFields['logo'];
            $file->move(public_path('images/logo'), $fileName);
        }

        DB::table('barangay_information')
        ->where('id', 1)
        ->update($formFields);

        return redirect('/maintenance/barangay-information');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
