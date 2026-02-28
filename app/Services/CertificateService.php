<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Spatie\Browsershot\Browsershot;

class CertificateService
{
    public function generatePdf(User $user, Course $course, Certificate $certificate): string
    {
        $html = view('certificates.certificate', compact('user', 'course', 'certificate'))
            ->render();

        $path = storage_path("app/certificates/cert-{$certificate->uuid}.pdf");

        // Ensure directory exists
        if (! is_dir(storage_path('app/certificates'))) {
            mkdir(storage_path('app/certificates'), 0755, true);
        }

        Browsershot::html($html)
            ->landscape()
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->savePdf($path);

        return $path;
    }
}