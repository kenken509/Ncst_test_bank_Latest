<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subject_codes')->insert([
            'name' => 'ACT106',
            'description' => 'Web Development 1',
            'department_id' => '1',
        ]);
        DB::table('subject_codes')->insert([
            'name' => 'ACT105',
            'description' => 'Object Oriented Programming',
            'department_id' => '1',
        ]);
        DB::table('subject_codes')->insert([
            'name' => 'IT106',
            'description' => 'Networking 1',
            'department_id' => '1',
        ]);

        DB::table('subject_codes')->insert([
            'name' => 'AE003',
            'description' => 'Managerial Economics',
            'department_id' => '2',
            'division_id'   => '2',
        ]);

        DB::table('subject_codes')->insert([
            'name' => 'ACC002',
            'description' => 'Conceptual Frameworks and Accounting Standards',
            'department_id' => '2',
            'division_id'   => '1',
        ]);

        
    }
}
