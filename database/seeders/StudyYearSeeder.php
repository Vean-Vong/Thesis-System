<?php

namespace Database\Seeders;

use App\Models\StudyYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $study_year = new StudyYear();
        $study_year->name = '2022-2023';
        $study_year->start = '2022-01-01';
        $study_year->end   = '2023-01-01';
        $study_year->is_active = 1;
        $study_year->save();

        $study_year = new StudyYear();
        $study_year->name = '2023-2024';
        $study_year->start = '2022-02-02';
        $study_year->end   = '2023-02-02';
        $study_year->save();

        $study_year = new StudyYear();
        $study_year->name = '2021-2022';
        $study_year->start = '2022-03-03';
        $study_year->end   = '2023-03-03';
        $study_year->save();

    }
}
