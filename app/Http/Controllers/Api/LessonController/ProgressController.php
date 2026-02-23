<?php

namespace App\Http\Controllers\Api\LessonController;

use App\Http\Controllers\Controller;
use App\Http\Requests\Progress\UpdateProgressRequest;
use App\Models\Lesson;
use App\Services\ProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function __construct(private ProgressService $progress) {}

    public function start(Request $request, Lesson $lesson): JsonResponse
    {
        $progress = $this->progress->start($request->user(), $lesson);

        return response()->json($progress);
    }

    public function complete(Request $request, Lesson $lesson): JsonResponse
    {
        $progress = $this->progress->complete($request->user(), $lesson);

        return response()->json([
            'message'  => 'Lesson marked as completed.',
            'progress' => $progress,
        ]);
    }

    public function update(UpdateProgressRequest $request, Lesson $lesson): JsonResponse
    {
        $this->progress->updateWatchTime(
            $request->user(),
            $lesson,
            $request->validated('watch_seconds')
        );

        return response()->json(['message' => 'Progress updated.']);
    }
}