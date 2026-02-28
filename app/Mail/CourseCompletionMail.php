<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Certificate;

class CourseCompletionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
    public User        $user,
    public Course      $course,
    public Certificate $certificate,  
) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "You completed: {$this->course->title} 🎉");
    }

   public function content(): Content
{
    return new Content(
        view: 'emails.course-completion',
        with: [
            'user'        => $this->user,
            'course'      => $this->course,
            'certificate' => $this->certificate,
        ]
    );
}
}