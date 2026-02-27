<?php

namespace App\Livewire\Lesson;

use App\Actions\Lesson\MarkLessonCompletedAction;
use App\Actions\Lesson\RecordLessonStartedAction;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class LessonPlayer extends Component
{
    public Course  $course;
    public Lesson  $lesson;
    public bool    $isCompleted  = false;
    public bool    $isEnrolled   = false;
    public int     $watchSeconds = 0;

    // ✅ 'slug' + 'lesson' match route segments /courses/{slug}/lessons/{lesson}
    // Livewire auto-resolves Lesson model via route model binding
    public function mount(string $slug, Lesson $lesson): void
    {
        $this->course = Course::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Ensure lesson belongs to this course
        abort_if($lesson->course_id !== $this->course->id, 404);

        $this->lesson = $lesson;

        $user = auth()->user();

        // Access guard
        if (! $lesson->is_free_preview) {
            abort_unless($user, 401, 'Please login to access this lesson.');
            abort_unless($user->isEnrolledIn($this->course->id), 403, 'Enroll to access this lesson.');
        }

        $this->isEnrolled = $user?->isEnrolledIn($this->course->id) ?? false;

        if ($user) {
            $progress           = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $this->isCompleted  = (bool) $progress?->completed_at;
            $this->watchSeconds = $progress?->watch_seconds ?? 0;

            // Record lesson as started
            app(RecordLessonStartedAction::class)->execute($user, $lesson);
        }
    }

    public function updateWatchSeconds(int $seconds): void
    {
        if (! auth()->check()) return;

        $this->watchSeconds = $seconds;

        LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $this->lesson->id)
            ->update(['watch_seconds' => $seconds]);
    }

    public function markComplete(): void
    {
        if (! auth()->check() || $this->isCompleted) return;

        app(MarkLessonCompletedAction::class)->execute(auth()->user(), $this->lesson);

        $this->isCompleted = true;
        $this->dispatch('lesson-completed', percentage: $this->course->completionPercentageFor(auth()->user()));
    }

    public function render()
    {
        return view('livewire.lesson.lesson-player', [
            'next'     => $this->lesson->next(),
            'previous' => $this->lesson->previous(),
        ]);
    }
}