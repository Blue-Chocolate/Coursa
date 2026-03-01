<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    public function generatePdf(User $user, Course $course, Certificate $certificate): string
    {
        $pdf = Pdf::loadView('certificates.certificate', compact('user', 'course', 'certificate'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'dpi'                         => 150,
                'defaultFont'                 => 'sans-serif',
                'isHtml5ParserEnabled'        => true,
                'isRemoteEnabled'             => true,  // allows loading images via URL
            ]);

        $relativePath = "certificates/cert-{$certificate->uuid}.pdf";

        Storage::put($relativePath, $pdf->output());

        return storage_path("app/{$relativePath}");
    }
}