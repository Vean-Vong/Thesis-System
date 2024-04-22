<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subject = [
            ['name' => 'Khmer', 'detail' => 'ភាសាខ្មែរ'],
            ['name' => 'Individual & Society', 'detail' => 'សិក្សាសង្គម'],
            ['name' => 'Mathematics', 'detail' => 'គណិតវិទ្យា'],
            ['name' => 'Physical Education', 'detail' => 'កីឡា'],
            ['name' => 'Art Education', 'detail' => 'អប់រំសិល្បៈ'],
            ['name' => 'English', 'detail' => 'អង់គ្លេស'],
        ];
        DB::table('subjects')->insert($subject);
    }
}
