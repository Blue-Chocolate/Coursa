<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Draft courses are only visible to enrolled users.
     * Published courses are visible to everyone including guests.
     */
    public function view(?User $user, Course $course): bool
    {
        if ($course->isPublished()) {
            return true;
        }

        return $user?->isEnrolledIn($course->id) ?? false;
    }

    /**
     * Only authenticated users can enroll in published courses.
     * Guests and draft course attempts are rejected.
     */
    public function enroll(User $user, Course $course): bool
    {
        return $course->isPublished();
    }
}