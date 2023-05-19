<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangayInformationController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LendController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MappingController;
use App\Http\Controllers\OfficialsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
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


Route::middleware(['guest'])->group(function() {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    
    Route::prefix('/forgot-password')->group(function() {   
        Route::get('/', [ForgotPasswordController::class, 'index']);
        Route::post('/username-check', [ForgotPasswordController::class, 'usernameCheck']);
        Route::get('/security-question', [ForgotPasswordController::class, 'securityCheck']);
        Route::post('/security-check', [ForgotPasswordController::class, 'questionCheck']);
        Route::get('/change-password', [ForgotPasswordController::class, 'passwordCheck']);
        Route::post('/change-password', [ForgotPasswordController::class, 'changePassword']);
    
    });
});

Route::middleware(['auth'])->group(function() {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::prefix('residents')->group(function() {
        Route::get('/', [ResidentController::class, 'index']);

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

        Route::get('/{id}', [ResidentController::class, 'show']);
        Route::get('/{id}/edit', [ResidentController::class, 'edit']);
        Route::post('/{id}/edit', [ResidentController::class, 'update']);
        Route::post('/{id}/archive', [ResidentController::class, 'archive']);
        Route::get('/{id}/recover', [ResidentController::class, 'recover']);
    });

    Route::prefix('blotters')->group(function() {
        Route::get('/', [BlotterController::class, 'index']);

        Route::prefix('/new')->group(function() {
            Route::get('/step-one', [BlotterController::class, 'create_StepOne']);
            Route::post('/step-one', [BlotterController::class, 'post_StepOne']);

            Route::get('/step-two', [BlotterController::class, 'create_StepTwo']);
            Route::post('/step-two', [BlotterController::class, 'post_StepTwo']);

            Route::get('/step-three', [BlotterController::class, 'create_StepThree']);
            Route::post('/step-three', [BlotterController::class, 'post_StepThree']);

            Route::get('/step-four', [BlotterController::class, 'create_StepFour']);
            Route::post('/step-four', [BlotterController::class, 'post_StepFour']);

            Route::get('/step-five', [BlotterController::class, 'create_StepFive']);
        });

        Route::get('/{id}', [BlotterController::class, 'show']);
        Route::get('/{id}/edit', [BlotterController::class, 'edit']);
        Route::post('/{id}/edit', [BlotterController::class, 'update']);
    });

    Route::prefix('certificates')->group(function() {
        Route::get('/', [CertificateController::class, 'index']);

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

        Route::post('/print', [CertificateController::class, 'print']);
        Route::get('/{id}', [CertificateController::class, 'show']);
        Route::get('/{id}/edit', [CertificateController::class, 'edit']);
        Route::post('/{id}/edit', [CertificateController::class, 'update']);
        Route::post('/{id}/archive', [CertificateController::class, 'archive']);
    });

    Route::prefix('mapping')->group(function() {
        Route::get('/', [MappingController::class, 'index']);
        Route::get('/new', [MappingController::class, 'create']);
        Route::post('/new', [MappingController::class, 'store']);
        Route::post('/update', [MappingController::class, 'update']);
        Route::get('/list', [MappingController::class, 'list']);
        Route::post('/{id}/archive', [MappingController::class, 'archive']);
        Route::get('/{id}/recover', [MappingController::class, 'recover']);
    });

    Route::prefix('inventory')->group(function() {
        Route::get('/', [InventoryController::class, 'index']);

        Route::prefix('/new')->group(function () {
            Route::get('/step-one', [InventoryController::class, 'create_StepOne']);
            Route::post('/step-one', [InventoryController::class, 'post_StepOne']);

            Route::get('/step-two', [InventoryController::class, 'create_StepTwo']);
            Route::post('/step-two', [InventoryController::class, 'post_StepTwo']);
        });
        Route::get('/{id}', [InventoryController::class, 'show']);
        Route::get('/{id}/edit', [InventoryController::class, 'edit']);
        Route::post('/{id}/edit', [InventoryController::class, 'update']);
        Route::post('/{id}/archive', [InventoryController::class, 'archive']);
        Route::get('/{id}/recover', [InventoryController::class, 'recover']);
    });

    Route::prefix('lend')->group(function () {
        Route::get('/', [LendController::class, 'index']);
        
        Route::prefix('new')->group(function () {
            Route::get('/step-one/{id?}', [LendController::class, 'create_StepOne']);
            Route::post('/step-one', [LendController::class, 'post_StepOne']);
    
            Route::get('/step-two', [LendController::class, 'create_StepTwo']);
            Route::post('/step-two', [LendController::class, 'post_StepTwo']);
        });

        Route::get('/{id}', [LendController::class, 'show']);
        Route::get('/{id}/edit', [LendController::class, 'edit']);
        Route::post('/{id}/edit', [LendController::class, 'update']);
        Route::post('/{id}/return', [LendController::class, 'return']);
    });

    Route::prefix('maintenance')->group(function() {

        Route::prefix('/archive')->group(function() {
            Route::get('/', [ArchiveController::class, 'index']);
            Route::get('/residents', [ArchiveController::class, 'residents']);
            Route::get('/inventory', [ArchiveController::class, 'inventory']);
            Route::get('/mapping', [ArchiveController::class, 'mapping']);
        });

        Route::prefix('/barangay-information')->group(function() {
            Route::get('/', [BarangayInformationController::class, 'index']);
            Route::get('/edit', [BarangayInformationController::class, 'edit']);
            Route::post('/edit', [BarangayInformationController::class, 'update']);
        });

        Route::prefix('/users')->group(function() {
            Route::get('/', [UserController::class, 'index']);

            Route::prefix('new')->group(function () {
                Route::get('/step-one', [UserController::class, 'create_StepOne']);
                Route::post('/step-one', [UserController::class, 'post_StepOne']);
        
                Route::get('/step-two', [UserController::class, 'create_StepTwo']);
                Route::post('/step-two', [UserController::class, 'post_StepTwo']);
            });

            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::post('/{id}/edit', [UserController::class, 'update']);
            Route::post('/{id}/delete', [UserController::class, 'destroy']);
        });

        Route::prefix('/settings')->group(function() {
            Route::get('/', [SettingsController::class, 'index']);

            Route::get('/positions', [SettingsController::class, 'index_positions']);
            Route::get('/civil-status', [SettingsController::class, 'index_civil_status']);
            Route::get('/occupations', [SettingsController::class, 'index_occupations']);
            Route::get('/religions', [SettingsController::class, 'index_religions']);
            Route::get('/security-questions', [SettingsController::class, 'index_security_questions']);
            Route::get('/genders', [SettingsController::class, 'index_genders']);
        });

        Route::prefix('/audit-trail')->group(function() {
            Route::get('/', [AuditTrailController::class, 'index']);
        });
    });

    Route::prefix('calendar')->group(function() {
        Route::get('/activity/{id}', [CalendarController::class, 'show']);
        Route::get('/activity/{id}/edit', [CalendarController::class, 'edit']);
        Route::post('/activity/{id}/edit', [CalendarController::class, 'update']);
        Route::post('/activity/{id}/delete', [CalendarController::class, 'destroy']);
    });

    Route::prefix('profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index']);
        Route::get('/edit', [ProfileController::class, 'edit']);
        Route::post('/edit', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    Route::prefix('officials')->group(function() {
        Route::get('/', [OfficialsController::class, 'index']);

        Route::prefix('new')->group(function () {
            Route::get('/step-one', [OfficialsController::class, 'create_StepOne']);
            Route::post('/step-one', [OfficialsController::class, 'post_StepOne']);
    
            Route::get('/step-two', [OfficialsController::class, 'create_StepTwo']);
            Route::post('/step-two', [OfficialsController::class, 'post_StepTwo']);
                
            Route::get('/step-three', [OfficialsController::class, 'create_StepThree']);
        });

        Route::get('/{id}', [OfficialsController::class, 'show']);
        Route::get('/{id}/edit', [OfficialsController::class, 'edit']);
        Route::post('/{id}/edit', [OfficialsController::class, 'update']);
    });
});