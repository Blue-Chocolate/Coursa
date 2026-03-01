<?php

namespace App\Actions\Courses;

use App\Mail\CourseCompletionMail;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\ProgressRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $allDone = $lessonIds->diff($completedIds)->isEmpty();

        if (! $allDone) {
            return false;
        }

      
        $lock = Cache::lock(
            "course_completion:{$user->id}:{$course->id}",
            10
        );

        if (! $lock->get()) {

            return false;
        }

        try {
         
            $inserted = $this->progress->insertCompletionOrIgnore($user->id, $course->id);

            if (! $inserted) {
                return false;
            }

            Certificate::insertOrIgnore([
                'user_id'    => $user->id,
                'course_id'  => $course->id,
                'uuid'       => (string) Str::uuid(),
                'issued_at'  => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $certificate = Certificate::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            Mail::to($user->email)->queue(
                new CourseCompletionMail($user, $course, $certificate)
            );

            return true;

        } finally {
            $lock->release();
        }
    }
}