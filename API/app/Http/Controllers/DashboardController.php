<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function summary()
    {

        $result['status'] = 200;

        try {

            $acYear = AcademicYear::where('is_active', 1)->first();

            $result['academic_classes'] = AcademicClass::count();
            $result['classes_this_term'] = AcademicClass::where('academic_year_id', $acYear->id)->count();
            $result['teachers'] = Teacher::count();
            $result['students'] = Student::count();
            $result['new_students'] = Student::whereBetween('created_at', [$acYear->start_date, $acYear->end_date])->count();
            $result['new_students_m'] = Student::whereBetween('created_at', [$acYear->start_date, $acYear->end_date])->where('gender', 1)->count();
            $result['new_students_f'] = Student::whereBetween('created_at', [$acYear->start_date, $acYear->end_date])->where('gender', 2)->count();
            $result['users'] = User::count();

            $student_in_month = [];

            for($i=1; $i<13; $i++){
                $student_in_month[$i-1] = Student::whereRaw("MONTH(created_at)=$i")->count();
            }

            $result['st_in_year'] = $student_in_month;

        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }
}
