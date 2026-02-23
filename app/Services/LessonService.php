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

        // Access control
        if (! $lesson->is_free_preview) {
            abort_if(! $user, 401, 'Login required to access this lesson.');
            abort_unless(
                $this->enrollments->isEnrolled($user->id, $course->id),
                403,
                'Enroll in the course to access this lesson.'
            );
        }

        return [
            'lesson'   => $lesson,
            'next'     => $lesson->next(),
            'previous' => $lesson->previous(),
            'progress' => $user ? $this->progress->find($user->id, $lesson->id) : null,
        ];
    }
}