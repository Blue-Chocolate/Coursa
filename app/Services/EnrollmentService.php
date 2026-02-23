<?php

namespace App\Services;

use App\Actions\Enrollment\EnrollUserAction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Http\Exceptions\HttpResponseException;

class EnrollmentService
{
    public function __construct(
        private EnrollUserAction              $enrollUser,
        private EnrollmentRepositoryInterface $enrollments,
    ) {}

    public function enroll(User $user, Course $course): Enrollment
    {
        if ($this->enrollments->isEnrolled($user->id, $course->id)) {
            // Idempotent — return existing enrollment silently
            return $this->enrollments->find($user->id, $course->id);
        }

        try {
            return $this->enrollUser->execute($user, $course);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json(['message' => $e->getMessage()], 422)
            );
        }
    }
}