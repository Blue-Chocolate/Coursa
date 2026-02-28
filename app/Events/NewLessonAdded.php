<?php

namespace App\Events;

use App\Models\Lesson;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLessonAdded
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Lesson $lesson) {}
}