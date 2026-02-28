<?php
// app/Notifications/NewLessonNotification.php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLessonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Lesson $lesson) {}

    /**
     * Deliver via database + email. Remove either channel as needed.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New lesson added: {$this->lesson->title}")
            ->greeting("Hi {$notifiable->name}!")
            ->line("A new lesson has been added to a course you're enrolled in.")
            ->line("**Course:** {$this->lesson->course->title}")
            ->line("**Lesson:** {$this->lesson->title}")
            ->action('Watch Now', url("/courses/{$this->lesson->course->slug}/lessons/{$this->lesson->id}"))
            ->line('Happy learning!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'lesson_id'   => $this->lesson->id,
            'lesson_title' => $this->lesson->title,
            'course_id'   => $this->lesson->course_id,
            'course_title' => $this->lesson->course->title,
            'course_slug' => $this->lesson->course->slug,
            'url'         => url("/courses/{$this->lesson->course->slug}/lessons/{$this->lesson->id}"),
        ];
    }
}