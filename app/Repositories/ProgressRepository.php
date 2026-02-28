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
        LessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['started_at' => now()]
        );

        return $this->find($userId, $lessonId);
    }

    /**
     * Atomic completion — uses a WHERE NULL guard so concurrent calls
     * cannot both set completed_at. Only the first UPDATE wins.
     * Returns the progress record regardless of which request won.
     */
    public function markCompleted(int $userId, int $lessonId): LessonProgress
    {
        // Atomic: only updates if not already completed
        $affected = DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->whereNull('completed_at')
            ->update(['completed_at' => now()]);

        // If $affected === 0, it was already completed — that's fine, return current state
        return $this->find($userId, $lessonId);
    }

    /**
     * Atomic watch seconds update — only moves forward, never regresses.
     * Prevents concurrent requests from overwriting a higher value with a lower one.
     */
    public function updateWatchSeconds(int $userId, int $lessonId, int $seconds): void
    {
        DB::table('lesson_progress')
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->where('watch_seconds', '<', $seconds) // only update if new value is greater
            ->update(['watch_seconds' => $seconds]);
    }

    public function completedLessonIds(int $userId, int $courseId): Collection
    {
        return LessonProgress::whereHas(
            'lesson', fn ($q) => $q->where('course_id', $courseId)
        )
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id');
    }

    public function insertCompletionOrIgnore(int $userId, int $courseId): bool
    {
        $inserted = DB::table('course_completions')->insertOrIgnore([
            'user_id'      => $userId,
            'course_id'    => $courseId,
            'completed_at' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return $inserted > 0;
    }
}