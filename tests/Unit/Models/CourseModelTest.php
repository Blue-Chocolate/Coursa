<?php 

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Models\Enrollment;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Course model', function () {

    it('scopes to published courses only', function () {
        Course::factory()->create(['status' => 'published']);
        Course::factory()->create(['status' => 'draft']);

        expect(Course::published()->count())->toBe(1);
    });

    it('calculates completion percentage for a user', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        $lessons = Lesson::factory()->count(4)->for($course)->create();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        // Complete 2 of 4 lessons
        foreach ($lessons->take(2) as $lesson) {
            LessonProgress::create([
                'user_id'      => $user->id,
                'lesson_id'    => $lesson->id,
                'completed_at' => now(),
            ]);
        }

        expect($course->completionPercentageFor($user))->toBe(50);
    });

    it('returns 0% completion for user with no progress', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();
        Lesson::factory()->count(3)->for($course)->create();

        expect($course->completionPercentageFor($user))->toBe(0);
    });

    it('returns 100% when all lessons are completed', function () {
        $user    = User::factory()->create();
        $course  = Course::factory()->create();
        $lessons = Lesson::factory()->count(3)->for($course)->create();

        foreach ($lessons as $lesson) {
            LessonProgress::create([
                'user_id'      => $user->id,
                'lesson_id'    => $lesson->id,
                'completed_at' => now(),
            ]);
        }

        expect($course->completionPercentageFor($user))->toBe(100);
    });

    it('returns 0% for course with no lessons', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        expect($course->completionPercentageFor($user))->toBe(0);
    });
});