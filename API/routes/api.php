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
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProRentalController;
use App\Http\Controllers\ProRentalsController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UtilityExpensesController;
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
    Route::get('/sales/report', [SaleController::class, 'getSalesReport']);
    Route::apiResource('sales', SaleController::class);
    // Custom route for monthly sales count
    Route::get('/sales/total-by-month', [SaleController::class, 'totalSalesPerMonth']);

    Route::get('/products/stock-quantities', [ProductController::class, 'stockQuantities']);

    Route::get('/reports/new-setup-details', [SaleController::class, 'newSetupDetails']);


    Route::apiResource('products', ProductController::class);
    Route::apiResource('stock', StockController::class);
    Route::apiResource('rentals', RentalController::class);
    Route::apiResource('filters', FilterController::class);
    Route::apiResource('services', ServiceController::class);

    Route::apiResource('utility_expenses', UtilityExpensesController::class);
    Route::apiResource('reports', ReportController::class);
    Route::get('/sales/installments-report', [ReportController::class, 'combinedReport']);
    Route::apiResource('setup', SetupController::class);
    Route::apiResource('installments', InstallmentController::class);
    Route::get('/sales-with-installments', [SaleController::class, 'salesWithInstallments']);
    // routes/api.php
    Route::post('/installments/sync', [InstallmentController::class, 'syncInstallments']);
    // fetch install history
    Route::get('/installments/{id}/payments', [InstallmentController::class, 'payments']);

    // Create payment for a installment
    Route::post('/installments/{installment}/payments', [PaymentController::class, 'store']);
    // Get payment invoice for a installment
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice']);
    // Get sale report
    Route::get('/sale/report', [SaleController::class, 'report']);
    // Get install Report
    Route::get('/installment-report', [SaleController::class, 'installReport']);
    // Get rental Report
    Route::get('/rental-report', [SaleController::class, 'rentalReport']);

    // Get stock report
    Route::get('/stocks-report', [StockController::class, 'report']);

    // Get rental report
    Route::get('/rental/report', [RentalController::class, 'report']);

    // Create payment for a rental
    Route::post('/rentals/{rental}/payments', [PaymentController::class, 'storeRentalPayment']);
    Route::get('/rentals/{rental}/payments', [RentalController::class, 'payments']);

    Route::apiResource('invoices', InvoiceController::class);

    Route::get('/rentals/{id}', [RentalController::class, 'rentalShow']);
    // Get payment invoice for a rental


});
