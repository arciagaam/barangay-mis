<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

Route::get('/inventory', function (Request $request) {
    
    $item = DB::table('inventory')
    ->where(function($query) use ($request) {
        $query->where('name', 'like', $request->search.'%')
        ->orWhere('quantity', 'like', $request->search.'%')
        ->orWhere('remarks', 'like', $request->search.'%');
    })
    ->orderBy('inventory.created_at', 'asc')
    ->limit(10)
    ->get();
    
    echo json_encode(['items' => $item]);
});

Route::post('/calendar', function (Request $request) {
    $activities = DB::table('activities')
    ->where('start_date', '>', "$request->year-$request->month-01")
    ->where('start_date', '<', "$request->year-$request->month-31")
    ->get();

    echo json_encode(['activities' => $activities]);
});

Route::put('/calendar', function (Request $request) {

    $validator = Validator::make($request->all(), [
        'start_date' => 'required',
        'end_date' => '',
        'start_time' => '',
        'end_time' => '',
        'name' => 'required',
        'description' => 'required',
        'is_all_day' => '',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $id = DB::table('activities')
    ->insertGetId($request->all());

    return json_encode(['id' => $id]);
});

