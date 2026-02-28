<?php 

use App\Actions\Lesson\MarkLessonCompletedAction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('MarkLessonCompletedAction', function () {

    it('marks a lesson as completed', function () {
        Mail::fake();

        $user   = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->for($course)->create();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        LessonProgress::create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $action = app(MarkLessonCompletedAction::class);
        $action->execute($user, $lesson);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        expect($progress->completed_at)->not->toBeNull();
    });

    it('is idempotent — completing twice does not duplicate', function () {
        Mail::fake();

        $user   = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->for($course)->create();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        LessonProgress::create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $action = app(MarkLessonCompletedAction::class);
        $action->execute($user, $lesson);
        $action->execute($user, $lesson);

        expect(LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->count()
        )->toBe(1);
    });
});
