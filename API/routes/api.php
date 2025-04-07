<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StockController;
use App\Models\Employee;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('app-settings-verify', [AppSettingController::class, 'verifySetting']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('summary', [DashboardController::class, 'summary']);

    //auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('verify-auth', [AuthController::class, 'verifyAuth']);
    Route::post('update-profile', [AuthController::class, 'changeProfile']);
    Route::post('update-password', [AuthController::class, 'changePassword']);


    Route::post('roles-create', [RoleController::class, 'store']);
    Route::post('roles-list', [RoleController::class, 'list']);
    Route::post('roles-show', [RoleController::class, 'show']);
    Route::post('roles-edit', [RoleController::class, 'edit']);
    Route::post('roles-update', [RoleController::class, 'update']);
    Route::post('roles-delete', [RoleController::class, 'delete']);
    Route::post('permissions-list', [PermissionController::class, 'list']);

    Route::post('users-create', [UserController::class, 'store']);
    Route::post('users-call-org-role', [UserController::class, 'callOrgRole']);
    Route::post('users-list', [UserController::class, 'list']);
    Route::post('users-init', [UserController::class, 'init']);
    Route::post('users-show', [UserController::class, 'show']);
    Route::post('users-update', [UserController::class, 'update']);
    Route::post('users-delete', [UserController::class, 'delete']);
    Route::post('users-reset-pw', [UserController::class, 'resetPassword']);


    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('sales', SaleController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('stock', StockController::class);
    Route::apiResource('rentals', RentalController::class);
    Route::apiResource('filters', FilterController::class);
    Route::apiResource('services', ServiceController::class);
});
