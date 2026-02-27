<?php

use App\Http\Controllers\Api\AuthController\AuthController;
use App\Http\Controllers\Api\CourseController\CourseController;
use App\Http\Controllers\Api\EnrollmentController\EnrollmentController;
use App\Http\Controllers\Api\LessonController\LessonController;
use App\Http\Controllers\Api\LessonController\ProgressController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('courses',              [CourseController::class, 'index']);
Route::get('courses/{slug}',       [CourseController::class, 'show']);
Route::get('courses/{slug}/lessons/{lesson}', [LessonController::class, 'show']);

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // Enrollments
    Route::post('courses/{slug}/enroll',  [EnrollmentController::class, 'store']);

    // Progress
    Route::post('lessons/{lesson}/start',    [ProgressController::class, 'start']);
    Route::post('lessons/{lesson}/complete', [ProgressController::class, 'complete']);
    Route::patch('lessons/{lesson}/progress',[ProgressController::class, 'update']);

    // User's enrolled courses
    Route::get('my/courses', [CourseController::class, 'enrolled']);
});


    Route::get('/register', Register::class)->name('register');
    Route::get('/login',    Login::class)->name('login');
    
Route::middleware('auth')->post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');