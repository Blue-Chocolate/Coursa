<?php

namespace App\Repositories;

use App\Models\LessonProgress;
use App\Repositories\Contracts\ProgressRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgressRepository implements ProgressRepositoryInterface
{
    public function find(int $userId, int $lessonId): ?LessonProgress
    {
        return LessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();
    }

    public function upsertStarted(int $userId, int $lessonId): LessonProgress
    {
        return LessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['started_at' => now(), 'watch_seconds' => 0]
        );
    }

    public function markCompleted(int $userId, int $lessonId): LessonProgress
    {
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['started_at' => now(), 'watch_seconds' => 0]
        );

        // Only set completed_at once — don't overwrite existing timestamp
        if (! $progress->completed_at) {
            $progress->update(['completed_at' => now()]);
        }

        return $progress->fresh();
    }

    public function updateWatchSeconds(int $userId, int $lessonId, int $seconds): void
    {
        // Use raw update to avoid race conditions on watch_seconds
        LessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->update(['watch_seconds' => $seconds]);
    }

    public function completedLessonIds(int $userId, int $courseId): Collection
    {
        return LessonProgress::where('user_id', $userId)
            ->whereHas('lesson', fn ($q) => $q->where('course_id', $courseId))
            ->whereNotNull('completed_at')
            ->pluck('lesson_id');
    }

    /**
     * Returns true if THIS call did the insert (winner).
     * Returns false if the row already existed (loser — email already sent).
     */
    public function insertCompletionOrIgnore(int $userId, int $courseId): bool
    {
        return (bool) DB::table('course_completions')->insertOrIgnore([
            'user_id'      => $userId,
            'course_id'    => $courseId,
            'completed_at' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}