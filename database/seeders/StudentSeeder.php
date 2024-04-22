<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = new Student();
        $student->code = '001';
        $student->last_name  = 'ចន';
        $student->first_name = 'សី';
        $student->last_name_latin = 'john';
        $student->first_name_latin = 'sey';
        $student->gender = 1;
        $student->dob  = '2009-12-30';

        $student->village_birth = 'ហប់';
        $student->commune_birth = 'ហប់';
        $student->district_birth = 'គាស់ក្រឡ';
        $student->province_birth = 'បាត់ដំបង';

        $student->village = 'ហប់';
        $student->commune = 'ហប់';
        $student->district = 'គាស់ក្រឡ';
        $student->province = 'បាត់ដំបង';

        $student->d_last_name = 'ហេង';
        $student->d_first_name = 'ចន';
        $student->d_job = 'Admin officer';
        $student->d_phone_number = '098080878';

        $student->m_last_name = 'រឿន';
        $student->m_first_name = 'មារី';
        $student->m_job = 'Admin officer';
        $student->m_phone_number = '098080878';

        $student->phone = '09898974';
        $student->student_status = 1;
        $student->from = 'btb';
        $student->other = 'good health';
        $student->status = 1;

        $student->g_last_name = 'ហេង';
        $student->g_first_name = 'ចន';
        $student->g_job = 'Admin officer';
        $student->g_phone_number = '098080878';
        $student->g_detail = 'father';
        $student->save();

        $student = new Student();
        $student->code = '002';
        $student->last_name  = 'ឆាយ';
        $student->first_name = 'វិរៈ';
        $student->last_name_latin = 'chay';
        $student->first_name_latin = 'vireak';
        $student->gender = 1;
        $student->dob  = '2009-12-30';

        $student->village_birth = 'ហប់';
        $student->commune_birth = 'ហប់';
        $student->district_birth = 'គាស់ក្រឡ';
        $student->province_birth = 'បាត់ដំបង';

        $student->village = 'ហប់';
        $student->commune = 'ហប់';
        $student->district = 'គាស់ក្រឡ';
        $student->province = 'បាត់ដំបង';

        $student->d_last_name = 'ជា';
        $student->d_first_name = 'ឆាយ';
        $student->d_job = 'Admin officer';
        $student->d_phone_number = '098080878';

        $student->m_last_name = 'សឹម';
        $student->m_first_name = 'ធីតា';
        $student->m_job = 'Admin officer';
        $student->m_phone_number = '098080878';

        $student->phone = '09898974';
        $student->student_status = 1;
        $student->from = 'btb';
        $student->other = 'good health';
        $student->status = 1;

        $student->g_last_name = 'សឹម';
        $student->g_first_name = 'ធីតា';
        $student->g_job = 'Admin officer';
        $student->g_phone_number = '098080878';
        $student->g_detail = 'father';
        $student->save();
    }
}
