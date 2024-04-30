<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = new Role();
        $role->name = 'Administrator';
        $role->save();

        $permissions = Permission::whereNotNull('parent_id')->get();

        foreach($permissions as $permission) {
            DB::table('permission_role')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission->id
            ]);
        }

        DB::table('role_user')->insert([
            'role_id' => $role->id,
            'user_id' => "6a0652a1-c83f-4233-9bdb-85ce5851dca7"
        ]);

    }
}
