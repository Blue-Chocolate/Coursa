<?php

namespace App\Repositories\Contracts;

use App\Models\LessonProgress;

interface ProgressRepositoryInterface
{
    public function find(int $userId, int $lessonId): ?LessonProgress;
    public function upsertStarted(int $userId, int $lessonId): LessonProgress;
    public function markCompleted(int $userId, int $lessonId): LessonProgress;
    public function updateWatchSeconds(int $userId, int $lessonId, int $seconds): void;
    public function completedLessonIds(int $userId, int $courseId): \Illuminate\Support\Collection;
    public function insertCompletionOrIgnore(int $userId, int $courseId): bool;
}