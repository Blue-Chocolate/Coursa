<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;


    public function view(?User $user, Course $course): bool
    {
        if ($course->isPublished()) {
            return true;
        }

        return $user?->isEnrolledIn($course->id) ?? false;
    }


    public function enroll(User $user, Course $course): bool
    {
        return $course->isPublished();
    }
}