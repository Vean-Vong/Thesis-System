<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
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

            $result['academic_classes'] = AcademicClass::count();
            $result['teachers'] = Teacher::count();
            $result['students'] = Student::count();
            $result['users'] = User::count();
        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }
}
