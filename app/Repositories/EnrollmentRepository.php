<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function find(int $userId, int $courseId): ?Enrollment
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function create(int $userId, int $courseId): Enrollment
    {
        // firstOrCreate = application-level idempotency
        // UNIQUE constraint = database-level idempotency
        return Enrollment::firstOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            ['enrolled_at' => now()]
        );
    }

    public function isEnrolled(int $userId, int $courseId): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }
}