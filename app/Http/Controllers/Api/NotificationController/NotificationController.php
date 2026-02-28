<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers\Api\NotificationController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Return the latest 20 notifications + unread count.
     * The `data` column is already cast to array by Laravel's DatabaseNotification.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()           // Laravel built-in relation
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'data'       => $n->data,   // ['lesson_id', 'lesson_title', 'course_title', 'course_slug', 'url']
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at,
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markRead(Request $request, string $id): JsonResponse
    {
        $request->user()->notifications()->findOrFail($id)->markAsRead();

        return response()->json(['ok' => true]);
    }
}