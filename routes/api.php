<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudyYearController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudyClassController;

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

Route::get('/guest', [AuthController::class, 'guest']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class)->only('index');
    Route::resource('/subjects', SubjectController::class);
    Route::resource('/study_years', StudyYearController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/students', StudentController::class);
    Route::resource('/rooms', RoomController::class);
    Route::resource('/grades', GradeController::class);
    Route::resource('/teachers', TeacherController::class);
    Route::resource('/studyclasses', StudyClassController::class);
    Route::get('/studyclasses-grade-subject/{id}', [StudyClassController::class, 'getGradeSubject']);
    Route::post('/studyclasses-filter-student', [StudyClassController::class, 'filterStudent']);
    Route::post('/studyclasses-find-student', [StudyClassController::class, 'findStudent']);
    Route::post("study-classes/{study_class}/attendances/form", [AttendanceController::class, 'form']);
    Route::post("study-classes/{study_class}/attendances/save", [AttendanceController::class, 'save']);
    Route::post("study-classes/{study_class}/attendances/list", [AttendanceController::class, 'list']);
    Route::post("study-classes/{study_class}/attendances/report", [AttendanceController::class, 'report']);
    Route::post("study-classes/{study_class}/scores/form", [ScoreController::class, 'form']);
    Route::post("study-classes/{study_class}/scores/save", [ScoreController::class, 'save']);
    Route::post("study-classes/{study_class}/scores/list", [ScoreController::class, 'list']);
    Route::post("study-classes/{study_class}/scores/ranking", [ScoreController::class, 'ranking']);
});
