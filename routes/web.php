<?php

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn () => view('pages.home'))->name('home');

// Course detail
Route::get('/courses/{slug}', function (string $slug) {
    $course = Course::published()->where('slug', $slug)->with(['level', 'lessons'])->firstOrFail();
    return view('pages.course', compact('course'));
})->name('course.show');

// Lesson player
Route::get('/courses/{slug}/lessons/{lesson}', function (string $slug, Lesson $lesson) {
    $course = Course::published()->where('slug', $slug)->firstOrFail();
    abort_if($lesson->course_id !== $course->id, 404);
    return view('pages.lesson', compact('course', 'lesson'));
})->name('lesson.show');

// My courses (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/my/courses', fn () => view('pages.my-courses'))->name('my.courses');
});

// Auth (Laravel Breeze / Fortify handles these)
