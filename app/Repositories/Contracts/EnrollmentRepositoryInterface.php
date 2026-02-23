<?php

namespace App\Repositories\Contracts;

use App\Models\Enrollment;

interface EnrollmentRepositoryInterface
{
    public function find(int $userId, int $courseId): ?Enrollment;
    public function create(int $userId, int $courseId): Enrollment;
    public function isEnrolled(int $userId, int $courseId): bool;
}