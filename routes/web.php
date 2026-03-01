<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Course\CourseDetail;
use App\Livewire\Course\MyCourses;
use App\Livewire\Home\CourseList;
use App\Livewire\Lesson\LessonPlayer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController\NotificationController;
use App\Http\Controllers\Api\CertificateController\CertificateController;

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login',    Login::class)->name('login');
});

Route::middleware('auth')->post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/',                                   CourseList::class)->name('home');
Route::get('/courses/{slug}',                     CourseDetail::class)->name('course.show');
Route::get('/courses/{slug}/lessons/{lesson}',    LessonPlayer::class)->name('lesson.show');

Route::middleware('auth')->group(function () {
    Route::get('/my/courses', MyCourses::class)->name('my.courses');
});

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/notifications',            [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all',  [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
});

Route::middleware('auth')->group(function () {
    Route::post('/lessons/{lesson}/complete', [
        \App\Http\Controllers\Api\LessonController\LessonController::class, 'complete'
    ])->name('lesson.complete');
});

// In routes/web.php — outside any auth group:
Route::post('/courses/{slug}/enroll', [
    \App\Http\Controllers\Api\LessonController\LessonController::class, 'enroll'
])->name('course.enroll')->middleware('auth');

Route::get('/certificates/{uuid}', [
    CertificateController::class, 'show'
])->name('certificate.show');