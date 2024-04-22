<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = new Teacher();
        $teacher->code = "01";
        $teacher->last_name  = "បាន​";
        $teacher->first_name = "រចនា";
        $teacher->last_name_latin = "Ban";
        $teacher->first_name_latin = "Reachana";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "02";
        $teacher->last_name  = "ឈឺន​";
        $teacher->first_name = "មាន";
        $teacher->last_name_latin = "Chheurn";
        $teacher->first_name_latin = "Mean";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "03";
        $teacher->last_name  = "ង៉ុយ​";
        $teacher->first_name = "សុគុណ";
        $teacher->last_name_latin = "Nguy";
        $teacher->first_name_latin = "Sukun";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "04";
        $teacher->last_name  = "គឹម​";
        $teacher->first_name = "ឡាង";
        $teacher->last_name_latin = "Kim";
        $teacher->first_name_latin = "Lang";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "05";
        $teacher->last_name  = "រ៉េត​";
        $teacher->first_name = "សារឿត";
        $teacher->last_name_latin = "Ret";
        $teacher->first_name_latin = "Sareurt";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "06";
        $teacher->last_name  = "ហាក់​";
        $teacher->first_name = "សុខលីន";
        $teacher->last_name_latin = "Hak";
        $teacher->first_name_latin = "Sok lin";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "07";
        $teacher->last_name  = "ស្ងួន​";
        $teacher->first_name = "ស៊ីណា";
        $teacher->last_name_latin = "Sngoun";
        $teacher->first_name_latin = "Sina";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();

        $teacher = new Teacher();
        $teacher->code = "08";
        $teacher->last_name  = "ទុយ​";
        $teacher->first_name = "រ័ត្នបុប្ផា";
        $teacher->last_name_latin = "Tuy";
        $teacher->first_name_latin = "Roth Bopha";
        $teacher->gender = 0;
        $teacher->dob  = '1998-8-18';
        $teacher->school_name = "សាលាបឋមសិក្សា២ធ្នូ";
        $teacher->school_code = "";
        $teacher->village = "";
        $teacher->commune = "";
        $teacher->district = "";
        $teacher->province = "";
        $teacher->phone = "";
        $teacher->photo_path = "";
        $teacher->status = true;
        $teacher->save();
    }
}
