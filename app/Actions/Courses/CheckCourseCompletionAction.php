<?php

namespace App\Actions\Courses;

use App\Mail\CourseCompletionMail;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\ProgressRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class CheckCourseCompletionAction
{
    public function __construct(
        private ProgressRepositoryInterface $progress,
    ) {}

    public function execute(User $user, Course $course): bool
    {
        $totalLessons = $course->lessons()->count();

        if ($totalLessons === 0) {
            return false;
        }

        $completedIds = $this->progress->completedLessonIds($user->id, $course->id);
        $lessonIds    = $course->lessons()->pluck('id');

        // All current lessons must be in the completed set
        $allDone = $lessonIds->diff($completedIds)->isEmpty();

        if (! $allDone) {
            return false;
        }

        // insertOrIgnore returns true only for the winning insert
        // This is the concurrency gate — only one email fires
        $inserted = $this->progress->insertCompletionOrIgnore($user->id, $course->id);

        if ($inserted) {
            Mail::to($user->email)->queue(new CourseCompletionMail($user, $course));
        }

        return $inserted;
    }
}