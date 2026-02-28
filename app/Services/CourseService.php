<?php

namespace App\Mail;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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

    public function attachments(): array
    {
        $path = app(CertificateService::class)
            ->generatePdf($this->user, $this->course, $this->certificate);

        return [
            Attachment::fromPath($path)
                ->as("certificate-{$this->course->slug}.pdf")
                ->withMime('application/pdf'),
        ];
    }
    public function findBySlug(string $slug, ?User $user = null): Course
{
    $course = $this->courses->findBySlug($slug);

    abort_if(! $course, 404, 'Course not found.');

    // Policy returns 404 for draft courses to avoid leaking existence
    if (! Gate::forUser($user)->allows('view', $course)) {
        abort(404, 'Course not found.');
    }

    return $course;
}
}