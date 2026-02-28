<?php
// app/Providers/EventServiceProvider.php

namespace App\Providers;

use App\Events\NewLessonAdded;
use App\Listeners\NotifyEnrolledUsers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewLessonAdded::class => [
            NotifyEnrolledUsers::class,
        ],
    ];
}