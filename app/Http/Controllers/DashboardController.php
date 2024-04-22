<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // students
        $total_students = DB::table('students')->count();
        $total_male_students = DB::table('students')
            ->where('gender', '=', 1)
            ->count();
        $total_female_students = DB::table('students')
            ->where('gender', '!=', 1)
            ->count();
        // teachers
        $total_teachers = DB::table('teachers')->count();
        //users
        $total_users = DB::table('users')->count();
        //study-class
        $study_class = DB::table('study_classes')->count();
        return response()->json([
            'total_students'        => $total_students,
            'total_male_students'   => $total_male_students,
            'tolal_female_students' => $total_female_students,
            'total_teachers'        => $total_teachers,
            'total_users'           => $total_users,
            'study_class'           => $study_class,
        ]);
    }
}
