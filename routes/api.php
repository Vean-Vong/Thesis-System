<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AcademicClassController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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
    Route::post('roles-update', [RoleController::class, 'update']);
    Route::post('roles-delete', [RoleController::class, 'delete']);
    Route::post('permissions-list', [PermissionController::class, 'list']);

    Route::post('users-create', [UserController::class, 'store']);
    Route::post('users-call-org-role', [UserController::class, 'callOrgRole']);
    Route::post('users-list', [UserController::class, 'list']);
    Route::post('users-show', [UserController::class, 'show']);
    Route::post('users-update', [UserController::class, 'update']);
    Route::post('users-delete', [UserController::class, 'delete']);
    Route::post('users-reset-pw', [UserController::class, 'resetPassword']);

    Route::post('schools-create', [SchoolController::class, 'store']);
    Route::post('schools-list', [SchoolController::class, 'list']);
    Route::post('schools-show', [SchoolController::class, 'show']);
    Route::post('schools-update', [SchoolController::class, 'update']);

    Route::post('academic-years-create', [AcademicYearController::class, 'store']);
    Route::post('academic-years-list', [AcademicYearController::class, 'list']);
    Route::post('academic-years-show', [AcademicYearController::class, 'show']);
    Route::post('academic-years-update', [AcademicYearController::class, 'update']);
    Route::post('academic-years-delete', [AcademicYearController::class, 'delete']);

    Route::post('academic-classes-create', [AcademicClassController::class, 'store']);
    Route::post('academic-classes-list', [AcademicClassController::class, 'list']);
    Route::post('academic-classes-show', [AcademicClassController::class, 'show']);
    Route::post('academic-classes-update', [AcademicClassController::class, 'update']);
    Route::post('academic-classes-delete', [AcademicClassController::class, 'delete']);
    Route::post('academic-classes-option', [AcademicClassController::class, 'option']);
    Route::post('academic-classes-detail', [AcademicClassController::class, 'detail']);
    Route::post('academic-classes-search-student', [AcademicClassController::class, 'searchStudent']);
    Route::post('academic-classes-add-student', [AcademicClassController::class, 'addStudent']);
    Route::post('academic-classes-list-student', [AcademicClassController::class, 'listStudent']);
    Route::post('academic-classes-remove-student', [AcademicClassController::class, 'removeStudent']);
    Route::post('academic-classes-move-student', [AcademicClassController::class, 'moveStudent']);
    Route::post('academic-classes-month', [AcademicClassController::class, 'getMonth']);

    Route::post('teachers-create', [TeacherController::class, 'store']);
    Route::post('teachers-list', [TeacherController::class, 'list']);
    Route::post('teachers-show', [TeacherController::class, 'show']);
    Route::post('teachers-update', [TeacherController::class, 'update']);
    Route::post('teachers-delete', [TeacherController::class, 'delete']);

    Route::post('students-create', [StudentController::class, 'store']);
    Route::post('students-list', [StudentController::class, 'list']);
    Route::post('students-show', [StudentController::class, 'show']);
    Route::post('students-update', [StudentController::class, 'update']);
    Route::post('students-delete', [StudentController::class, 'delete']);

    Route::post('exam-form', [ExamController::class, 'form']);
    Route::post('exam-save', [ExamController::class, 'save']);
    Route::post('exam-show', [ExamController::class, 'show']);

    Route::post('attendances-form', [AttendanceController::class, 'form']);
    Route::post('attendances-save', [AttendanceController::class, 'save']);
    Route::post('attendances-show', [AttendanceController::class, 'show']);
});
