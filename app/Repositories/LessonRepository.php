<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Repositories\Contracts\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class LessonRepository implements LessonRepositoryInterface
{
    public function findInCourse(int $lessonId, int $courseId): ?Lesson
    {
        return Lesson::where('id', $lessonId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function orderedByCourse(int $courseId): Collection
    {
        return Lesson::where('course_id', $courseId)
            ->orderBy('order')
            ->get();
    }

    public function allCompletedByUser(int $courseId, int $userId): SupportCollection
    {
        return LessonProgress::whereHas('lesson', fn ($q) => $q->where('course_id', $courseId))
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id');
    }
}