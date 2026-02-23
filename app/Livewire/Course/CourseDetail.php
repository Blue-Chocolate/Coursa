<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\LessonProgress;
use Livewire\Component;

class CourseDetail extends Component
{
    public Course $course;

    public function mount(Course $course): void
    {
        $this->course = $course->load(['level', 'lessons' => fn ($q) => $q->orderBy('order')]);
    }

    public function render()
    {
        $user            = auth()->user();
        $isEnrolled      = $user?->isEnrolledIn($this->course->id) ?? false;
        $completedIds    = collect();
        $completionPct   = 0;

        if ($user && $isEnrolled) {
            $completedIds  = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
                ->whereNotNull('completed_at')
                ->pluck('lesson_id');

            $total        = $this->course->lessons->count();
            $completionPct = $total > 0 ? (int) round(($completedIds->count() / $total) * 100) : 0;
        }

        return view('livewire.course.course-detail', compact(
            'isEnrolled', 'completedIds', 'completionPct'
        ));
    }
}