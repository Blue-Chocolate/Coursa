<?php

namespace App\Livewire\Lesson;

use App\Models\LessonProgress;
use Livewire\Attributes\On;
use Livewire\Component;

class ProgressBar extends Component
{
    public int $courseId;
    public int $percentage  = 0;
    public int $completed   = 0;
    public int $total       = 0;

    public function mount(int $courseId): void
    {
        $this->courseId = $courseId;
        $this->recalculate();
    }

    // Listens for the event fired by LessonPlayer when a lesson is marked complete
    #[On('lesson-completed')]
    public function onLessonCompleted(): void
    {
        $this->recalculate();
    }

    private function recalculate(): void
    {
        $user    = auth()->user();
        $course  = \App\Models\Course::with('lessons')->find($this->courseId);

        if (! $user || ! $course) return;

        $lessonIds     = $course->lessons->pluck('id');
        $this->total   = $lessonIds->count();

        $this->completed = LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->whereNotNull('completed_at')
            ->count();

        $this->percentage = $this->total > 0
            ? (int) round(($this->completed / $this->total) * 100)
            : 0;
    }

    public function render()
    {
        return view('livewire.lesson.progress-bar');
    }
}