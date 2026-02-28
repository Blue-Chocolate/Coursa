<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    /**
     * Free preview lessons are accessible to everyone including guests.
     * Paid lessons require authentication and enrollment.
     */
    public function view(?User $user, Lesson $lesson): bool
    {
        if ($lesson->is_free_preview) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isEnrolledIn($lesson->course_id);
    }

    /**
     * Only enrolled users can mark a lesson as complete.
     */
    public function complete(User $user, Lesson $lesson): bool
    {
        return $user->isEnrolledIn($lesson->course_id);
    }
}