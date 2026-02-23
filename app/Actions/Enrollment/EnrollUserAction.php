<?php

namespace App\Actions\Enrollment;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EnrollUserAction
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments,
    ) {}

    /**
     * @throws \Exception if course is not published
     */
    public function execute(User $user, Course $course): Enrollment
    {
        if (! $course->isPublished()) {
            throw new \Exception('Cannot enroll in a draft course.');
        }

        // DB transaction + unique constraint = safe under concurrency
        return DB::transaction(function () use ($user, $course) {
            return $this->enrollments->create($user->id, $course->id);
        });
    }
}