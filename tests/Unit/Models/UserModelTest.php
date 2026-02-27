<?php 
use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('User model', function () {

    it('knows when it is enrolled in a course', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        expect($user->isEnrolledIn($course->id))->toBeFalse();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        expect($user->isEnrolledIn($course->id))->toBeTrue();
    });

    it('knows when it has completed a course', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        expect($user->hasCompletedCourse($course->id))->toBeFalse();

        CourseCompletion::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        expect($user->hasCompletedCourse($course->id))->toBeTrue();
    });

    it('returns lesson progress for a specific lesson', function () {
        $user   = User::factory()->create();
        $lesson = Lesson::factory()->create();

        expect($user->progressForLesson($lesson->id))->toBeNull();

        LessonProgress::create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        expect($user->progressForLesson($lesson->id))->not->toBeNull();
    });
});