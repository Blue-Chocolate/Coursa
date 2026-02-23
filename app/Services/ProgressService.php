<?php

namespace App\Services;

use App\Actions\Lesson\MarkLessonCompletedAction;
use App\Actions\Lesson\RecordLessonStartedAction;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\ProgressRepositoryInterface;

class ProgressService
{
    public function __construct(
        private RecordLessonStartedAction     $recordStarted,
        private MarkLessonCompletedAction     $markCompleted,
        private ProgressRepositoryInterface   $progress,
        private EnrollmentRepositoryInterface $enrollments,
    ) {}

    public function start(User $user, Lesson $lesson): LessonProgress
    {
        $this->guardEnrollment($user, $lesson);

        return $this->recordStarted->execute($user, $lesson);
    }

    public function complete(User $user, Lesson $lesson): LessonProgress
    {
        $this->guardEnrollment($user, $lesson);

        return $this->markCompleted->execute($user, $lesson);
    }

    public function updateWatchTime(User $user, Lesson $lesson, int $seconds): void
    {
        $this->guardEnrollment($user, $lesson);

        $this->progress->updateWatchSeconds($user->id, $lesson->id, $seconds);
    }

    private function guardEnrollment(User $user, Lesson $lesson): void
    {
        abort_unless(
            $lesson->is_free_preview || $this->enrollments->isEnrolled($user->id, $lesson->course_id),
            403,
            'You are not enrolled in this course.'
        );
    }
}