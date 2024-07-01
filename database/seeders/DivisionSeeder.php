<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            'name' => 'Accountancy',
            'department_id' => '2'
        ]);

        DB::table('divisions')->insert([
            'name' => 'Business Administration',
            'department_id' => '2'
        ]);

        DB::table('divisions')->insert([
            'name' => 'Custom Administration',
            'department_id' => '2'
        ]);

        DB::table('divisions')->insert([
            'name' => 'Office Management',
            'department_id' => '2'
        ]);
    }
}
