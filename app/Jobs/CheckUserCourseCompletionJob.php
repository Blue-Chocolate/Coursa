<?php

namespace App\Jobs;

use App\Actions\Courses\CheckCourseCompletionAction;
use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckUserCourseCompletionJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    public function __construct(
        private int $userId,
        private int $courseId,
    ) {}

    public function handle(CheckCourseCompletionAction $action): void
    {
        $user   = User::find($this->userId);
        $course = Course::find($this->courseId);

        if (! $user || ! $course) {
            return;
        }

        $action->execute($user, $course);
    }
}