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
    ->leftJoin('households', 'households.id', '=', 'residents.household_id')
    ->leftJoin('religions', 'religions.id', '=', 'residents.religion_id')
    ->leftJoin('occupations', 'occupations.id', '=', 'residents.occupation_id')
    ->leftJoin('civil_status', 'civil_status.id', '=', 'residents.civil_status_id')
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

Route::get('/inventory/{id}', function ($id) {
    
    $item = DB::table('inventory')
    ->where('id', $id)
    ->orderBy('inventory.created_at', 'asc')
    ->first();
    
    echo json_encode(['item' => $item]);
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

    if($request->is_all_day == 0 && ((!$request->start_time || $request->start_time == '') || (!$request->end_time || $request->end_time == ''))) {
        return response()->json(['message' => 'Start Time and End Time is required'], 422);
    }
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }


    $id = DB::table('activities')
    ->insertGetId($request->all());

    echo json_encode(['id' => $id]);
});

/*
    API ROUTES FOR POSITIONS
*/

Route::get('/positions/{id}', function(Request $request, $id) {
    $data = DB::table('official_positions')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Occupation not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/positions', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $data = DB::table('official_positions')
    ->insert(['name' => $request->name]);

    addToLog('Create', "New Position Created");
    
    echo json_encode(['data' => $data]);
});

Route::patch('/positions/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Position ID: $id Updated");

    DB::table('official_positions')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/positions/{id}/delete', function(Request $request, $id) {

    DB::table('official_positions')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Position ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR CIVIL STATUS
*/

Route::get('/civil_status/{id}', function(Request $request, $id) {
    $data = DB::table('civil_status')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Civil Status not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/civil_status', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Civil Status Created");

    $data = DB::table('civil_status')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/civil_status/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Civil Status ID: $id Updated");

    DB::table('civil_status')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/civil_status/{id}/delete', function(Request $request, $id) {

    DB::table('civil_status')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Civil Status ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR OCCUPATION
*/

Route::get('/occupations/{id}', function(Request $request, $id) {
    $data = DB::table('occupations')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Occupation not found.'], 422);
    }

    addToLog('Create', "New Occupation Created");

    echo json_encode(['data' => $data]);
});

Route::put('/occupations', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Occupation Created");

    $data = DB::table('occupations')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/occupations/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Occupation ID: $id Updated");

    DB::table('occupations')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/occupations/{id}/delete', function(Request $request, $id) {

    DB::table('occupations')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Occupation ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR RELIGIONS
*/

Route::get('/religions/{id}', function(Request $request, $id) {
    $data = DB::table('religions')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Occupation not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/religions', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Religion Created");

    $data = DB::table('religions')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/religions/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Religion ID: $id Updated");

    DB::table('religions')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/religions/{id}/delete', function(Request $request, $id) {

    DB::table('religions')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Religion ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR SECURITY QUESTIONS
*/

Route::get('/security_questions/{id}', function(Request $request, $id) {
    $data = DB::table('security_questions')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Occupation not found.'], 422);
    }
    

    echo json_encode(['data' => $data]);
});

Route::put('/security_questions', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Security Question Created");

    $data = DB::table('security_questions')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/security_questions/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Security Question ID: $id Updated");

    DB::table('security_questions')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/security_questions/{id}/delete', function(Request $request, $id) {

    DB::table('security_questions')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Security Question ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR GENDERS
*/

Route::get('/genders/{id}', function(Request $request, $id) {
    $data = DB::table('genders')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Occupation not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/genders', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Gender Created");

    $data = DB::table('genders')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/genders/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Gender ID: $id Updated");

    DB::table('genders')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/genders/{id}/delete', function(Request $request, $id) {

    DB::table('genders')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Gender ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

