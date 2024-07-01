<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(1)->create([
            'name'  => 'paul',
            'email'     => 'admin@gmail.com',
            'role'      => 'admin',
        ]);

        \App\Models\User::factory(1)->create([
            'name'      => 'Melvz',
            'email'     => 'coadmin@gmail.com',
            'role'      => 'co-admin',
        ]);

        \App\Models\User::factory(1)->create([
            'name'          => 'Ken',
            'email'         => 'dephead@gmail.com',
            'role'          => 'department head',
            'department_id'  => '2',
        ]);

        \App\Models\User::factory(1)->create([
            'name'          => 'Aries',
            'email'         => 'faculty@gmail.com',
            'role'          => 'faculty',
            'department_id' => '2',
            'division_id'   => '1',
        ]);

        \App\Models\User::factory(1)->create([
            'name'          => 'Calix',
            'email'         => 'csd@gmail.com',
            'role'          => 'faculty',
            'department_id' => '1',
        ]);
    }
}
