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
    ->leftJoin('streets', 'streets.id', 'households.street_id')
    ->select('residents.*', 'households.*', 'residents.id as resident_id', 'households.id as household_id', 'religions.name as religion', 'occupations.name as occupation', 'civil_status.name as civil_status', 'streets.name as street')
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
        ->leftJoin('streets', 'streets.id', 'households.street_id')
        ->orWhere('others', 'like', $request->search.'%');
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
    ->where('start_date', '>=', "$request->year-$request->month-01")
    ->where('start_date', '<=', "$request->year-$request->month-31")
    ->where('archived', 0)
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
        'details' => 'required',
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
        return response()->json(['message' => 'Position not found.'], 422);
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
        return response()->json(['message' => 'Religion not found.'], 422);
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
        return response()->json(['message' => 'Security Question not found.'], 422);
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
    API ROUTES FOR SEX
*/

Route::get('/sex/{id}', function(Request $request, $id) {
    $data = DB::table('sex')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Sex not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/sex', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Sex Created");

    $data = DB::table('sex')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/sex/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Sex ID: $id Updated");

    DB::table('sex')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/sex/{id}/delete', function(Request $request, $id) {

    DB::table('sex')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Sex ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR ARCHIVE REASONS
*/

Route::get('/archive_reasons/{id}', function(Request $request, $id) {
    $data = DB::table('archive_reasons')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Reason not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/archive_reasons', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Archive Reason Created");

    $data = DB::table('archive_reasons')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/archive_reasons/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Archive Reason ID: $id Updated");

    DB::table('archive_reasons')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/archive_reasons/{id}/delete', function(Request $request, $id) {

    DB::table('archive_reasons')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Archive Reason ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

/*
    API ROUTES FOR STREETS
*/

Route::get('/streets/{id}', function(Request $request, $id) {
    $data = DB::table('streets')
    ->where('id', $id)
    ->first();
    
    if(!$data) {
        return response()->json(['message' => 'Street not found.'], 422);
    }

    echo json_encode(['data' => $data]);
});

Route::put('/streets', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Create', "New Street Created");

    $data = DB::table('streets')
    ->insert(['name' => $request->name]);
    
    echo json_encode(['data' => $data]);
});

Route::patch('/streets/{id}', function(Request $request, $id) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    addToLog('Update', "Street ID: $id Updated");

    DB::table('streets')
    ->where('id', $id)
    ->update(['name' => $request->name]);

    return response()->json(['message' => 'success'], 200);
});

Route::get('/streets/{id}/delete', function(Request $request, $id) {

    DB::table('streets')
    ->where('id', $id)
    ->delete();

    addToLog('Delete', "Street ID: $id Deleted");

    return response()->json(['message' => 'success'], 200);
});

Route::get('mappings', function(Request $request) {

    $data = DB::table('mappings')
    ->join('residents', 'residents.id', 'mappings.resident_id')
    ->join('households', 'households.id', 'residents.household_id')
    ->join('streets', 'streets.id', 'households.street_id')
    ->select('*', 'mappings.id as id', 'streets.name as street_name')
    ->get();

    echo json_encode(['data' => $data]);
});
