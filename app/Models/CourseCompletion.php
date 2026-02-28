<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCompletion extends Model
{  use HasFactory;
    protected $fillable = ['user_id', 'course_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

public function completionPercentageFor(User $user): int
{
    return cache()->remember(
        "user:{$user->id}:course:{$this->id}:completion",
        now()->addMinutes(10),
        function () use ($user) {
            $total = $this->lessons()->count();
            if ($total === 0) return 0;

            $completed = LessonProgress::whereIn('lesson_id', $this->lessons()->pluck('id'))
                ->where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count();

            return (int) round(($completed / $total) * 100);
        }
    );
}

// Call this whenever a lesson is marked complete:
public function bustCompletionCache(int $userId): void
{
    cache()->forget("user:{$userId}:course:{$this->id}:completion");
}
}