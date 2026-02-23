<?php

namespace App\Http\Controllers\Api\LessonController;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Services\LessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        private LessonService $lessons,
        private CourseService $courses,
    ) {}

    public function show(Request $request, string $slug, int $lesson): JsonResponse
    {
        $course = $this->courses->findBySlug($slug);
        $data   = $this->lessons->getLesson($course, $lesson, $request->user());

        return response()->json($data);
    }
}