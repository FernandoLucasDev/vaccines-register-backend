<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\EmployeeVaccineController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaccinesController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::prefix('users')->group(function () {
        Route::post('/create', [UserController::class, 'store']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::get('/show', [UserController::class, 'show']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('employees')->group(function () {
        Route::post('/create', [EmployeesController::class, 'store']);
        Route::put('/update/{id}', [EmployeesController::class, 'update']);
        Route::get('/show', [EmployeesController::class, 'show']);
        Route::delete('/delete/{id}', [EmployeesController::class, 'destroy']);

        Route::put('/vaccine/relate/{id}', [EmployeeVaccineController::class, 'store']);
        Route::get('/vaccine/show/{id}', [EmployeeVaccineController::class, 'getEmployeeVaccines']);
        Route::put('/{employeeId}/vaccines/{vaccineId}', [EmployeeVaccineController::class, 'update']);
    });

    Route::prefix('vaccines')->group(function () {
        Route::post('/create', [VaccinesController::class, 'store']);
        Route::put('/update/{id}', [VaccinesController::class, 'update']);
        Route::get('/show', [VaccinesController::class, 'show']);
        Route::delete('/delete/{id}', [VaccinesController::class, 'destroy']);
    });

    Route::prefix('report')->group(function () {
        Route::get('/generate', [ReportsController::class, 'generateUnvaccinatedReport']);
        Route::get('/status/{id}', [ReportsController::class, 'getReportStatus']);
    });
});
