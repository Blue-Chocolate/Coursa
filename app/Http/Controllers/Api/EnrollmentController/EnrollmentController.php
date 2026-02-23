<?php

namespace App\Http\Controllers\Api\EnrollmentController;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentService $enrollments,
        private CourseService     $courses,
    ) {}

    public function store(Request $request, string $slug): JsonResponse
    {
        $course     = $this->courses->findBySlug($slug);
        $enrollment = $this->enrollments->enroll($request->user(), $course);

        return response()->json([
            'message'    => 'Enrolled successfully.',
            'enrollment' => $enrollment,
        ], 201);
    }
}