<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grade = new Grade();
        $grade->name = 'Grade1';
        $grade->save();

        $grade = new Grade();
        $grade->name = 'Grade2';
        $grade->save();

        $grade = new Grade();
        $grade->name = 'Grade3';
        $grade->save();

        $grade = new Grade();
        $grade->name = 'Grade4';
        $grade->save();

        $grade = new Grade();
        $grade->name = 'Grade5';
        $grade->save();

        $grade = new Grade();
        $grade->name = 'Grade6';
        $grade->save();
    }
}
