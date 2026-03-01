<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        private CourseRepositoryInterface $courses,
    ) {}

    public function listPublished(array $filters = []): LengthAwarePaginator
    {
        return $this->courses->allPublished($filters);
    }

    public function findBySlug(string $slug, ?User $user = null): Course
{
    $course = $this->courses->findBySlug($slug);

    if (! $course) {
        abort(404, 'Course not found.');
    }

    // Draft courses are only visible to enrolled users
    if (! $course->isPublished()) {
        if (! $user || ! $user->isEnrolledIn($course->id)) {
            abort(404, 'Course not found.');
        }
    }

    return $course;
}

    public function enrolledCourses(User $user): LengthAwarePaginator
    {
        return $this->courses->enrolledByUser($user->id);
    }
}