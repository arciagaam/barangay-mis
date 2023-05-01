<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/residents', function (Request $request) {
    
    $residents = DB::table('residents')
    ->join('households', 'households.id', '=', 'residents.household_id')
    ->join('religions', 'religions.id', '=', 'residents.religion_id')
    ->join('occupations', 'occupations.id', '=', 'residents.occupation_id')
    ->join('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
    ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status')
    ->where('residents.archived', '=', '0')
    ->where(function($query) use ($request) {
        $query->where(DB::raw('CONCAT(first_name, " ", middle_name, " ", last_name)'), 'like', $request->search . '%')
        ->orWhere('first_name', 'like', $request->search.'%')
        ->orWhere('middle_name', 'like', $request->search.'%')
        ->orWhere('last_name', 'like', $request->search.'%')
        ->orWhere('nickname', 'like', $request->search.'%')
        ->orWhere('birth_date', 'like', $request->search.'%')
        ->orWhere('place_of_birth', 'like', $request->search.'%')
        ->orWhere('house_number', 'like', $request->search.'%')
        ->orWhere('purok', 'like', $request->search.'%')
        ->orWhere('block', 'like', $request->search.'%')
        ->orWhere('lot', 'like', $request->search.'%')
        ->orWhere('others', 'like', $request->search.'%')
        ->orWhere('subdivision', 'like', $request->search.'%');
    })
    ->orderBy('residents.created_at', 'asc')
    ->limit(10)
    ->get();
    
    echo json_encode(['residents' => $residents]);
});

