<?php

namespace App\Actions\Lesson;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Repositories\Contracts\ProgressRepositoryInterface;

class RecordLessonStartedAction
{
    public function __construct(
        private ProgressRepositoryInterface $progress,
    ) {}

    public function execute(User $user, Lesson $lesson): LessonProgress
    {
        return $this->progress->upsertStarted($user->id, $lesson->id);
    }
}