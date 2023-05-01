<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index']);
Route::post('/authenticate', [AuthController::class, 'index']);


Route::get('/dashboard', function() {
    return view('pages.admin.dashboard.index');
});

Route::prefix('residents')->group(function() {
    Route::get('/', [ResidentController::class, 'index']);
    Route::get('/{id}', [ResidentController::class, 'show']);
    Route::get('/{id}/edit', [ResidentController::class, 'edit']);
    Route::post('/{id}/edit', [ResidentController::class, 'update']);
    Route::get('/{id}/archive', [ResidentController::class, 'archive']);

    Route::prefix('/new')->group(function() {
        Route::get('/step-one', [ResidentController::class, 'create_StepOne']);
        Route::post('/step-one', [ResidentController::class, 'post_StepOne']);

        Route::get('/step-two', [ResidentController::class, 'create_StepTwo']);
        Route::post('/step-two', [ResidentController::class, 'post_StepTwo']);

        Route::get('/step-three', [ResidentController::class, 'create_StepThree']);
        Route::post('/step-three', [ResidentController::class, 'post_StepThree']);

        Route::get('/step-four', [ResidentController::class, 'create_StepFour']);
        Route::post('/step-four', [ResidentController::class, 'post_StepFour']);
    });
});

Route::prefix('blotter')->group(function() {
    Route::get('/', [BlotterController::class, 'index']);
});

Route::prefix('certificates')->group(function() {
    Route::get('/', [CertificateController::class, 'index']);
    Route::get('/{id}', [CertificateController::class, 'show']);
    Route::get('/{id}/edit', [CertificateController::class, 'edit']);
    Route::post('/{id}/edit', [CertificateController::class, 'update']);
    Route::get('/{id}/archive', [CertificateController::class, 'archive']);

    Route::prefix('/new')->group(function() {
        Route::get('/step-one', [CertificateController::class, 'create_StepOne']);
        Route::post('/step-one', [CertificateController::class, 'post_StepOne']);

        Route::get('/step-two', [CertificateController::class, 'create_StepTwo']);
        Route::post('/step-two', [CertificateController::class, 'post_StepTwo']);

        Route::get('/step-three', [CertificateController::class, 'create_StepThree']);
        Route::post('/step-three', [CertificateController::class, 'post_StepThree']);

        Route::get('/step-four', [CertificateController::class, 'create_StepFour']);
        Route::post('/step-four', [CertificateController::class, 'post_StepFour']);
    });
});

Route::middleware(['auth'])->group(function() {
});