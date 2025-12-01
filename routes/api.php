<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\EstimateController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PayrollController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Core Resources
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('estimates', EstimateController::class);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('payrolls', PayrollController::class);
});
