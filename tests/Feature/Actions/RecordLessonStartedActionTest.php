<?php 

use App\Actions\Lesson\RecordLessonStartedAction;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('RecordLessonStartedAction', function () {

    it('creates a progress record on first visit', function () {
        $user   = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $action = app(RecordLessonStartedAction::class);
        $action->execute($user, $lesson);

        $this->assertDatabaseHas('lesson_progress', [
            'user_id'      => $user->id,
            'lesson_id'    => $lesson->id,
            'completed_at' => null,
        ]);
    });

    it('does not overwrite existing progress on revisit', function () {
        $user   = User::factory()->create();
        $lesson = Lesson::factory()->create();

        LessonProgress::create([
            'user_id'        => $user->id,
            'lesson_id'      => $lesson->id,
            'watch_seconds'  => 120,
            'completed_at'   => now(),
        ]);

        $action = app(RecordLessonStartedAction::class);
        $action->execute($user, $lesson);

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        // Should NOT reset watch_seconds or completed_at
        expect($progress->watch_seconds)->toBe(120)
            ->and($progress->completed_at)->not->toBeNull();
    });
});
