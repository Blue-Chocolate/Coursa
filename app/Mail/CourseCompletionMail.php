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

    public string $pdfPath;

    public function __construct(
        public User        $user,
        public Course      $course,
        public Certificate $certificate,
    ) {
        // Generate PDF immediately so the file exists when the queued job runs
        $this->pdfPath = app(CertificateService::class)
            ->generatePdf($this->user, $this->course, $this->certificate);
    }

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
        return [
            Attachment::fromPath($this->pdfPath)
                ->as("certificate-{$this->course->slug}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}