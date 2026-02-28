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
    public function complete(Request $request, int $lesson): JsonResponse
{
    $lessonModel = \App\Models\Lesson::findOrFail($lesson);

    app(\App\Actions\Lesson\MarkLessonCompletedAction::class)
        ->execute($request->user(), $lessonModel);

    return response()->json(['message' => 'Lesson marked as complete.']);
}
public function enroll(Request $request, string $slug): JsonResponse
{
    $course = $this->courses->findBySlug($slug);

    app(\App\Actions\Enrollment\EnrollUserAction::class)
        ->execute($request->user(), $course);

    return response()->json(['message' => 'Enrolled successfully.'], 200);
}
}