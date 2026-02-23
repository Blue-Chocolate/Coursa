<?php

namespace App\Actions\Lesson;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Repositories\Contracts\ProgressRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Actions\Courses\CheckCourseCompletionAction;

class MarkLessonCompletedAction
{
    public function __construct(
        private ProgressRepositoryInterface    $progress,
        private CheckCourseCompletionAction    $checkCompletion,
    ) {}

    public function execute(User $user, Lesson $lesson): LessonProgress
    {
        return DB::transaction(function () use ($user, $lesson) {
            $progress = $this->progress->markCompleted($user->id, $lesson->id);

            // Check completion inside the same transaction
            $this->checkCompletion->execute($user, $lesson->course);

            return $progress;
        });
    }
}