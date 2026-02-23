<?php

namespace App\Livewire\Lesson;

use App\Actions\MarkLessonCompletedAction;
use App\Actions\RecordLessonStartedAction;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Livewire\Component;

class LessonPlayer extends Component
{
    public Course  $course;
    public Lesson  $lesson;
    public bool    $isCompleted  = false;
    public bool    $isEnrolled   = false;
    public int     $watchSeconds = 0;

    public function mount(Course $course, Lesson $lesson): void
    {
        $this->course  = $course;
        $this->lesson  = $lesson;

        $user = auth()->user();

        // Access guard
        if (! $lesson->is_free_preview) {
            abort_unless($user, 401);
            abort_unless($user->isEnrolledIn($course->id), 403);
        }

        $this->isEnrolled = $user?->isEnrolledIn($course->id) ?? false;

        // Load existing progress
        if ($user) {
            $progress           = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $this->isCompleted  = (bool) $progress?->completed_at;
            $this->watchSeconds = $progress?->watch_seconds ?? 0;

            // Record started
            if ($user && ($lesson->is_free_preview || $this->isEnrolled)) {
                app(RecordLessonStartedAction::class)->execute($user, $lesson);
            }
        }
    }

    /**
     * Called by Alpine.js periodically to save watch time.
     */
    public function updateWatchSeconds(int $seconds): void
    {
        if (! auth()->check()) return;

        $this->watchSeconds = $seconds;

        LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $this->lesson->id)
            ->update(['watch_seconds' => $seconds]);
    }

    /**
     * Triggered by the completion confirmation modal (Alpine.js Feature #2).
     */
    public function markComplete(): void
    {
        if (! auth()->check() || $this->isCompleted) return;

        app(MarkLessonCompletedAction::class)->execute(auth()->user(), $this->lesson);

        $this->isCompleted = true;
        $this->dispatch('lesson-completed', percentage: $this->getCoursePercentage());
    }

    private function getCoursePercentage(): int
    {
        return $this->course->completionPercentageFor(auth()->user());
    }

    public function render()
    {
        return view('livewire.lesson.lesson-player', [
            'next'     => $this->lesson->next(),
            'previous' => $this->lesson->previous(),
        ]);
    }
}