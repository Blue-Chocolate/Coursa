<?php

namespace App\Http\Controllers\Api\CourseController;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(private CourseService $courses) {}

    public function index(Request $request): JsonResponse
    {
        $courses = $this->courses->listPublished($request->only('level_id', 'search'));

        return response()->json($courses);
    }

    public function show(string $slug): JsonResponse
    {
        $course = $this->courses->findBySlug($slug);

        return response()->json($course->load(['level', 'lessons']));
    }

    public function enrolled(Request $request): JsonResponse
    {
        $courses = $this->courses->enrolledCourses($request->user());

        return response()->json($courses);
    }
}