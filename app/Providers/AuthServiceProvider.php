<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Policies\CoursePolicy;
use App\Policies\LessonPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class => CoursePolicy::class,
        Lesson::class => LessonPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}