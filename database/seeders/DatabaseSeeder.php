<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Database\Seeders\GradeSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\StudentSeeder;
use Database\Seeders\SubjectSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'password' => bcrypt('password'),
        // ]);
        $user = new User();
        $user->name  = 'admin';
        $user->email = 'admin@admin.com';
        $user->password = bcrypt('password');
        $user->save();
        $this->call([
            PermissionSeeder::class,
            SubjectSeeder::class,
            RoomSeeder::class,
            StudentSeeder::class,
            GradeSeeder::class,
            TeacherSeeder::class,
            StudyYearSeeder::class,
            StudyClassSeeder::class,
        ]);

        $role = new Role();
        $role->name = "admin";
        $role->display_name = "Admin";
        $role->save();

        $permission =  DB::table('permissions')
            ->pluck('id');

        foreach ($permission as $item) {
            DB::table('permission_role')->insert([
                "role_id" => $role->id,
                "permission_id" => $item,
            ]);
        }
        $roles = DB::table('roles')
            ->pluck('id');
        foreach ($roles as $it) {
            DB::table('role_user')->insert([
                "user_id" => $user->id,
                'role_id' => $it,
            ]);
        }
    }
}
