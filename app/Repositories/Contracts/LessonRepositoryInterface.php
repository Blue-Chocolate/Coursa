<?php

namespace App\Repositories\Contracts;

use App\Models\Lesson;

interface LessonRepositoryInterface
{
    public function findInCourse(int $lessonId, int $courseId): ?Lesson;
    public function orderedByCourse(int $courseId): \Illuminate\Database\Eloquent\Collection;
    public function allCompletedByUser(int $courseId, int $userId): \Illuminate\Support\Collection;
}