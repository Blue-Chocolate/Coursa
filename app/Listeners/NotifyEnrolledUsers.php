<?php

namespace App\Listeners;

use App\Events\NewLessonAdded;
use App\Models\Enrollment;
use App\Notifications\NewLessonNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyEnrolledUsers implements ShouldQueue
{
    use InteractsWithQueue;

  
    private const CHUNK_SIZE = 100;

    public function handle(NewLessonAdded $event): void
    {
        $lesson = $event->lesson;

        Enrollment::with('user')
            ->where('course_id', $lesson->course_id)
            ->chunkById(self::CHUNK_SIZE, function ($enrollments) use ($lesson) {
                foreach ($enrollments as $enrollment) {
                    $enrollment->user->notify(
                        new NewLessonNotification($lesson)
                    );
                }
            });
    }
}