<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                "name" => "study_class_access",
                "display_name" => "Studyclass Access",
            ],
            [
                "name" => "teacher_access",
                "display_name" => "Teacher Access",
            ],
            [
                "name" => "student_access",
                "display_name" => "Student Access",
            ],
            [
                "name" => "subject_access",
                "display_name" => "Subject Access",
            ],
            [
                "name" => "room_access",
                "display_name" => "Room Access",
            ],
            [
                "name" => "grade_access",
                "display_name" => "Grade Access",
            ],
            [
                "name" => "study_year_access",
                "display_name" => "Studyyear Access",
            ],
            [
                "name" => "role_access",
                "display_name" => "Role Access",
            ],
            [
                "name" => "user_access",
                "display_name" => "User Access",
            ],
        ]);
    }
}
