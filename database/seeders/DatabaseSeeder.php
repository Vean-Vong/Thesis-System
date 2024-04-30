<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => '6a0652a1-c83f-4233-9bdb-85ce5851dca7',
            'school_id' => 57,
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('Pwd@12345'),
            'owner' => true,
            'is_super' => true,
        ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);
    }
}
