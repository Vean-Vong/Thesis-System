<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new Permission();
        $user->name = 'user';
        $user->display_name = 'អ្នកប្រើប្រាស់';
        $user->save();

        $user_access = new Permission();
        $user_access->parent_id = $user->id;
        $user_access->name = 'view_users';
        $user_access->display_name = 'គ្រប់គ្រងអ្នកប្រើប្រាស់';
        $user_access->save();

        $user_access = new Permission();
        $user_access->parent_id = $user->id;
        $user_access->name = 'create_users';
        $user_access->display_name = 'បង្កើតអ្នកប្រើប្រាស់';
        $user_access->save();

        $user_access = new Permission();
        $user_access->parent_id = $user->id;
        $user_access->name = 'edit_users';
        $user_access->display_name = 'កែប្រែអ្នកប្រើប្រាស់';
        $user_access->save();

        $user_access = new Permission();
        $user_access->parent_id = $user->id;
        $user_access->name = 'delete_users';
        $user_access->display_name = 'លុបអ្នកប្រើប្រាស់';
        $user_access->save();

        $role = new Permission();
        $role->name = 'role';
        $role->display_name = 'តួនាទី';
        $role->save();

        // $role_access = new Permission();
        // $role_access->parent_id = $role->id;
        // $role_access->name = 'view_roles';
        // $role_access->display_name = 'តួនាទី';
        // $role_access->save();

        $role_access = new Permission();
        $role_access->parent_id = $role->id;
        $role_access->name = 'create_roles';
        $role_access->display_name = ' បង្កើតតួនាទី';
        $role_access->save();

        $role_access = new Permission();
        $role_access->parent_id = $role->id;
        $role_access->name = 'edit_roles';
        $role_access->display_name = ' កែប្រែតួនាទី';
        $role_access->save();

        $role_access = new Permission();
        $role_access->parent_id = $role->id;
        $role_access->name = 'delete_roles';
        $role_access->display_name = ' លុបតួនាទី';
        $role_access->save();

        $school = new Permission();
        $school->name = 'school';
        $school->display_name = 'សាលារៀន';
        $school->save();

        $school_access = new Permission();
        $school_access->parent_id = $school->id;
        $school_access->name = 'school_access';
        $school_access->display_name = 'គ្រប់គ្រងសាលារៀន';
        $school_access->save();

        $manage_student_class = new Permission();
        $manage_student_class->name = 'manage_student_class';
        $manage_student_class->display_name = 'សិស្សតាមថ្នាក់';
        $manage_student_class->save();

        $manage_student_class_access = new Permission();
        $manage_student_class_access->parent_id = $manage_student_class->id;
        $manage_student_class_access->name = 'manage_student_class_access';
        $manage_student_class_access->display_name = 'គ្រប់គ្រងសិស្សតាមថ្នាក់';
        $manage_student_class_access->save();

        $teacher = new Permission();
        $teacher->name = 'teacher';
        $teacher->display_name = 'គ្រូបង្រៀន';
        $teacher->save();

        $teacher_list = new Permission();
        $teacher_list->parent_id = $teacher->id;
        $teacher_list->name = 'teacher_list';
        $teacher_list->display_name = 'មើលគ្រូបង្រៀន';
        $teacher_list->save();

        $teacher_create = new Permission();
        $teacher_create->parent_id = $teacher->id;
        $teacher_create->name = 'teacher_create';
        $teacher_create->display_name = 'បង្កើតគ្រូបង្រៀន';
        $teacher_create->save();

        $teacher_edit = new Permission();
        $teacher_edit->parent_id = $teacher->id;
        $teacher_edit->name = 'teacher_edit';
        $teacher_edit->display_name = 'កែប្រែគ្រូបង្រៀន';
        $teacher_edit->save();

        $teacher_delete = new Permission();
        $teacher_delete->parent_id = $teacher->id;
        $teacher_delete->name = 'teacher_delete';
        $teacher_delete->display_name = 'លុបគ្រូបង្រៀន';
        $teacher_delete->save();

        $student = new Permission();
        $student->name = 'student';
        $student->display_name = 'សិស្ស';
        $student->save();

        $student_list = new Permission();
        $student_list->parent_id = $student->id;
        $student_list->name = 'student_list';
        $student_list->display_name = 'មើលសិស្ស';
        $student_list->save();

        $student_create = new Permission();
        $student_create->parent_id = $student->id;
        $student_create->name = 'student_create';
        $student_create->display_name = 'បង្កើតសិស្ស';
        $student_create->save();

        $student_edit = new Permission();
        $student_edit->parent_id = $student->id;
        $student_edit->name = 'student_edit';
        $student_edit->display_name = 'កែប្រែសិស្ស';
        $student_edit->save();

        $student_delete = new Permission();
        $student_delete->parent_id = $student->id;
        $student_delete->name = 'student_delete';
        $student_delete->display_name = 'លុបសិស្ស';
        $student_delete->save();

        $academic_year = new Permission();
        $academic_year->name = 'academic_year';
        $academic_year->display_name = 'ឆ្នាំសិក្សា';
        $academic_year->save();

        $academic_year_list = new Permission();
        $academic_year_list->parent_id = $academic_year->id;
        $academic_year_list->name = 'academic_year_list';
        $academic_year_list->display_name = 'មើលឆ្នាំសិក្សា';
        $academic_year_list->save();

        $academic_year_create = new Permission();
        $academic_year_create->parent_id = $academic_year->id;
        $academic_year_create->name = 'academic_year_create';
        $academic_year_create->display_name = 'បង្កើតឆ្នាំសិក្សា';
        $academic_year_create->save();

        $academic_year_edit = new Permission();
        $academic_year_edit->parent_id = $academic_year->id;
        $academic_year_edit->name = 'academic_year_edit';
        $academic_year_edit->display_name = 'កែប្រែឆ្នាំសិក្សា';
        $academic_year_edit->save();

        $academic_year_delete = new Permission();
        $academic_year_delete->parent_id = $academic_year->id;
        $academic_year_delete->name = 'academic_year_delete';
        $academic_year_delete->display_name = 'លុបឆ្នាំសិក្សា';
        $academic_year_delete->save();

        $academic_class = new Permission();
        $academic_class->name = 'academic_class';
        $academic_class->display_name = 'ថ្នាក់រៀន';
        $academic_class->save();

        $academic_class_list = new Permission();
        $academic_class_list->parent_id = $academic_class->id;
        $academic_class_list->name = 'academic_class_list';
        $academic_class_list->display_name = 'មើលថ្នាក់រៀន';
        $academic_class_list->save();

        $academic_class_create = new Permission();
        $academic_class_create->parent_id = $academic_class->id;
        $academic_class_create->name = 'academic_class_create';
        $academic_class_create->display_name = 'បង្កើតថ្នាក់រៀន';
        $academic_class_create->save();

        $academic_class_edit = new Permission();
        $academic_class_edit->parent_id = $academic_class->id;
        $academic_class_edit->name = 'academic_class_edit';
        $academic_class_edit->display_name = 'កែប្រែថ្នាក់រៀន';
        $academic_class_edit->save();

        $academic_class_delete = new Permission();
        $academic_class_delete->parent_id = $academic_class->id;
        $academic_class_delete->name = 'academic_class_delete';
        $academic_class_delete->display_name = 'លុបថ្នាក់រៀន';
        $academic_class_delete->save();

        $score = new Permission();
        $score->name = 'score';
        $score->display_name = 'ពិន្ទុ';
        $score->save();

        $score_list = new Permission();
        $score_list->parent_id = $score->id;
        $score_list->name = 'score_list';
        $score_list->display_name = 'មើលពិន្ទុ';
        $score_list->save();

        $score_save = new Permission();
        $score_save->parent_id = $score->id;
        $score_save->name = 'score_save';
        $score_save->display_name = 'បញ្ចូលពិន្ទុ';
        $score_save->save();

        $attendance = new Permission();
        $attendance->name = 'attendance';
        $attendance->display_name = 'វត្តមាន';
        $attendance->save();

        $attendance_list = new Permission();
        $attendance_list->parent_id = $attendance->id;
        $attendance_list->name = 'attendance_list';
        $attendance_list->display_name = 'មើលវត្តមាន';
        $attendance_list->save();

        $attendance_save = new Permission();
        $attendance_save->parent_id = $attendance->id;
        $attendance_save->name = 'attendance_save';
        $attendance_save->display_name = 'បញ្ចូលវត្តមាន';
        $attendance_save->save();

    }
}
