<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            DepartmentSeeder::class,
            DivisionSeeder::class,
            AdminSeeder::class,
            SubjectCodeSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,

            // SubjectSeeder::class,
            // InstructorSeeder::class,
            // SectionSeeder::class,
            // StudentSeeder::class,
            // QuizSeeder::class,
            // QuizQuestionSeeder::class,
            // QuizChoicesSeeder::class,
            // WebPostSeeder::class,
            // CommentSeeder::class,
            // CarouselImageSeeder::class,
            // WebAnalysysSeeder::class,
            // GuestUserSeeder::class,
            // SchoolYearSeeder::class,
            // Instructor_Sections_Seeder::class,
        ]);
    }
}
