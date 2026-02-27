<?php

namespace App\Livewire\Course;

use App\Models\LessonProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class MyCourses extends Component
{
    public function mount(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
        }
    }

    public function render()
    {
        $user = auth()->user();

        $enrollments = $user->enrollments()
            ->with(['course.level', 'course.lessons'])
            ->latest('enrolled_at')
            ->get()
            ->map(function ($enrollment) use ($user) {
                $course    = $enrollment->course;
                $lessonIds = $course->lessons->pluck('id');
                $total     = $lessonIds->count();

                $completedIds = LessonProgress::where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->whereNotNull('completed_at')
                    ->pluck('lesson_id');

                $enrollment->completion_pct      = $total > 0
                    ? (int) round(($completedIds->count() / $total) * 100)
                    : 0;
                $enrollment->completed_count     = $completedIds->count();
                $enrollment->total_lessons       = $total;
                $enrollment->is_course_completed = $user->hasCompletedCourse($course->id);
                $enrollment->next_lesson         = $course->lessons
                    ->whereNotIn('id', $completedIds->toArray())
                    ->sortBy('order')
                    ->first();

                return $enrollment;
            });

        return view('pages.my-courses', compact('enrollments'));
    }
}