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
    public Course $course;
    public Lesson $lesson;
    public bool   $isCompleted  = false;
    public bool   $isEnrolled   = false;
    public int    $watchSeconds = 0;

    public function mount(string $slug, Lesson $lesson): void
    {
        $this->course = Course::published()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_if($lesson->course_id !== $this->course->id, 404);

        $this->lesson = $lesson;

        $this->authorize('view', $lesson);

        $user = auth()->user();
        $this->isEnrolled = $user?->isEnrolledIn($this->course->id) ?? false;

        if ($user) {
            $progress           = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $this->isCompleted  = (bool) $progress?->completed_at;
            $this->watchSeconds = $progress?->watch_seconds ?? 0;

            app(RecordLessonStartedAction::class)->execute($user, $lesson);
        }
    }

    /**
     * Called from Alpine via $wire.updateWatchSeconds(seconds).
     * The frontend debounces this to fire at most every 5 seconds.
     * The repository uses a WHERE watch_seconds < :seconds guard so
     * concurrent calls never regress the value.
     */
    public function updateWatchSeconds(int $seconds): void
    {
        if (! auth()->check()) return;

        $this->watchSeconds = $seconds;

        app(\App\Repositories\Contracts\ProgressRepositoryInterface::class)
            ->updateWatchSeconds(auth()->id(), $this->lesson->id, $seconds);
    }

    public function markComplete(): void
    {
        if ($this->isCompleted) return;

        $this->authorize('complete', $this->lesson);

        app(MarkLessonCompletedAction::class)->execute(auth()->user(), $this->lesson);

        $this->isCompleted = true;
        $this->dispatch(
            'lesson-completed',
            percentage: $this->course->completionPercentageFor(auth()->user())
        );
    }

    public function render()
    {
        return view('livewire.lesson.lesson-player', [
            'next'     => $this->lesson->next(),
            'previous' => $this->lesson->previous(),
        ]);
    }

    public function getVideoEmbedId(): string
    {
        $url = $this->lesson->video_url;

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return $m[1];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return $m[1];
        }

        return $url;
    }
}