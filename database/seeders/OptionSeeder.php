<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // question 1
        DB::table('options')->insert([
            'option' => 'Grace Hopper',
            'question_id' => '1',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Ada Lovelace',
            'question_id' => '1',
            'isCorrect' => 'true'
            
        ]);

        DB::table('options')->insert([
            'option' => 'Anita Borg',
            'question_id' => '1',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Katherine Johnson',
            'question_id' => '1',
            'isCorrect' => 'false'
        ]);

        // question 2
        DB::table('options')->insert([
            'option' => 'Luca Pacioli',
            'question_id' => '2',
            'isCorrect' => 'true'
        ]);

        DB::table('options')->insert([
            'option' => 'Arthur Blank',
            'question_id' => '2',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Bob Newhart',
            'question_id' => '2',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Gary Boomer',
            'question_id' => '2',
            'isCorrect' => 'false'
        ]);

        // question 3
        DB::table('options')->insert([
            'option' => 'Cascading Style Sheet',
            'question_id' => '3',
            'isCorrect' => 'true'
        ]);

        DB::table('options')->insert([
            'option' => 'Creative Style Sheet',
            'question_id' => '3',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Colorful Style Sheet',
            'question_id' => '3',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'Computer Style Sheet',
            'question_id' => '3',
            'isCorrect' => 'false'
        ]);

        //question 4
        DB::table('options')->insert([
            'option' => 'laptop.png',
            'question_id' => '4',
            'isCorrect' => 'true'
        ]);

        DB::table('options')->insert([
            'option' => 'desktop.png',
            'question_id' => '4',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'keyboard.png',
            'question_id' => '4',
            'isCorrect' => 'false'
        ]);

        DB::table('options')->insert([
            'option' => 'monitor.png',
            'question_id' => '4',
            'isCorrect' => 'false'
        ]);

    }
}
