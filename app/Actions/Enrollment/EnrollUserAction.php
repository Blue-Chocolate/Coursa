<?php

namespace App\Actions\Enrollment;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EnrollUserAction
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollments,
    ) {}

    public function execute(User $user, Course $course): Enrollment
    {
        Gate::authorize('enroll', $course);

        return DB::transaction(function () use ($user, $course) {
            
            DB::table('users')
                ->where('id', $user->id)
                ->lockForUpdate()
                ->first();

            // Re-check inside the lock — the state may have changed while waiting
            $existing = $this->enrollments->findByUserAndCourse($user->id, $course->id);

            if ($existing) {
                // Idempotent — return existing enrollment instead of throwing
                return $existing;
            }

            return $this->enrollments->create($user->id, $course->id);
        });
    }
}