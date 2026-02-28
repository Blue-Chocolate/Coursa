<?php

namespace App\Http\Controllers\Api\CertificateController;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    public function show(string $uuid): \Illuminate\View\View
    {
        $certificate = Certificate::where('uuid', $uuid)
            ->with(['user', 'course.level'])
            ->firstOrFail();

        return view('certificates.certificate', [
            'certificate' => $certificate,
            'user'        => $certificate->user,
            'course'      => $certificate->course,
        ]);
    }
}