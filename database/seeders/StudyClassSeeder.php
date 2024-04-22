<?php

namespace Database\Seeders;

use App\Models\StudyClass;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyclass = new StudyClass();
        $studyclass->name = '១ក';
        $studyclass->teacher_id = 1;
        $studyclass->grade_id = 1;
        $studyclass->room_id = 1;
        $studyclass->study_year_id = 1;
        $studyclass->status = 1;
        $studyclass->save();

        $studyclass = new StudyClass();
        $studyclass->name = '២ក';
        $studyclass->teacher_id = 2;
        $studyclass->grade_id = 2;
        $studyclass->room_id = 2;
        $studyclass->study_year_id = 2;
        $studyclass->status = 1;
        $studyclass->save();

        $studyclass = new StudyClass();
        $studyclass->name = '៣ក';
        $studyclass->teacher_id = 3;
        $studyclass->grade_id = 3;
        $studyclass->room_id = 3;
        $studyclass->study_year_id = 3;
        $studyclass->status = 1;
        $studyclass->save();

        $studyclass = new StudyClass();
        $studyclass->name = '៤ក';
        $studyclass->teacher_id = 4;
        $studyclass->grade_id = 4;
        $studyclass->room_id = 4;
        $studyclass->study_year_id = 4;
        $studyclass->status = 1;
        $studyclass->save();
    }
}
