<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Repositories\Contracts\LessonRepositoryInterface;
use App\Repositories\Contracts\ProgressRepositoryInterface;

class LessonService
{
    public function __construct(
        private LessonRepositoryInterface    $lessons,
        private EnrollmentRepositoryInterface $enrollments,
        private ProgressRepositoryInterface  $progress,
    ) {}

 public function getLesson(Course $course, int $lessonId, ?User $user): array
{
    $lesson = $this->lessons->findInCourse($lessonId, $course->id);

    abort_if(! $lesson, 404, 'Lesson not found.');

    // Policy handles free preview, auth, and enrollment checks
    if (! app(\Illuminate\Auth\Access\Gate::class)->inspect('view', $lesson)->allowed()) {
        abort_if(! $user, 401, 'Login required.');
        abort(403, 'Enroll in the course to access this lesson.');
    }

    return [
        'lesson'   => $lesson,
        'next'     => $lesson->next(),
        'previous' => $lesson->previous(),
        'progress' => $user ? $this->progress->find($user->id, $lesson->id) : null,
    ];
}
}