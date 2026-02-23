<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'student@lms.test')->first();

        $webCourse     = Course::where('slug', 'web-development-fundamentals')->first();
        $laravelCourse = Course::where('slug', 'laravel-from-scratch')->first();

        // ── Enroll student in both published courses ───────────────────────────
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $webCourse->id],
            ['enrolled_at' => now()->subDays(10)]
        );

        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $laravelCourse->id],
            ['enrolled_at' => now()->subDays(5)]
        );

        // ── Seed progress: student has completed all lessons in webCourse ──────
        // This will trigger the completion check manually below
        $webLessons = $webCourse->lessons()->orderBy('order')->get();

        foreach ($webLessons as $lesson) {
            LessonProgress::firstOrCreate(
                ['user_id' => $student->id, 'lesson_id' => $lesson->id],
                [
                    'started_at'    => now()->subDays(9),
                    'completed_at'  => now()->subDays(8),  // all done
                    'watch_seconds' => $lesson->duration_seconds ?? 0,
                ]
            );
        }

        // Manually insert course_completion since we bypassed the Action
        \Illuminate\Support\Facades\DB::table('course_completions')->insertOrIgnore([
            'user_id'      => $student->id,
            'course_id'    => $webCourse->id,
            'completed_at' => now()->subDays(8),
            'created_at'   => now()->subDays(8),
            'updated_at'   => now()->subDays(8),
        ]);

        // ── Seed progress: student is halfway through laravelCourse ───────────
        $laravelLessons = $laravelCourse->lessons()->orderBy('order')->take(3)->get();

        foreach ($laravelLessons as $lesson) {
            LessonProgress::firstOrCreate(
                ['user_id' => $student->id, 'lesson_id' => $lesson->id],
                [
                    'started_at'    => now()->subDays(4),
                    'completed_at'  => now()->subDays(3),
                    'watch_seconds' => $lesson->duration_seconds ?? 0,
                ]
            );
        }

        // Started but not completed lesson 4
        $lesson4 = $laravelCourse->lessons()->where('order', 4)->first();
        if ($lesson4) {
            LessonProgress::firstOrCreate(
                ['user_id' => $student->id, 'lesson_id' => $lesson4->id],
                [
                    'started_at'    => now()->subDay(),
                    'completed_at'  => null,            // in progress
                    'watch_seconds' => 900,
                ]
            );
        }

        $this->command->info('✅ Enrollments and progress seeded.');
        $this->command->info('   → student@lms.test: Web Dev course COMPLETED, Laravel course ~50%');
    }
}