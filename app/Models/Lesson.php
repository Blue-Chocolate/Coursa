<?php

namespace App\Models;

use App\Events\NewLessonAdded;
use App\Jobs\CheckUserCourseCompletionJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order',
        'video_url',
        'duration_seconds',
        'is_free_preview',
    ];

    protected $casts = [
        'is_free_preview'  => 'boolean',
        'duration_seconds' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    // ── Navigation helpers ────────────────────────────────────────────────────
    public function next(): ?Lesson
    {
        return $this->course->lessons()
            ->where('order', '>', $this->order)
            ->first();
    }

    public function previous(): ?Lesson
    {
        return $this->course->lessons()
            ->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isAccessibleBy(?User $user): bool
    {
        if ($this->is_free_preview) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isEnrolledIn($this->course_id);
    }

    public function progressFor(User $user): ?LessonProgress
    {
        return $this->progress()->where('user_id', $user->id)->first();
    }

    // ── Boot ──────────────────────────────────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();

        // Fire event when a new lesson is added to a published course
        static::created(function (Lesson $lesson) {
            if ($lesson->course?->isPublished()) {
                NewLessonAdded::dispatch($lesson);
            }
        });

        // When a lesson is deleted, re-evaluate completion for all enrolled users.
        // Chunked into queue jobs to avoid timing out on large courses.
        // Example: 5,000 enrolled users = 50 jobs of 100 users each, not 1 blocking request.
        static::deleted(function (Lesson $lesson) {
            $courseId = $lesson->course_id;

            $lesson->course
                ->enrollments()
                ->select('user_id')
                ->chunk(100, function ($enrollments) use ($courseId) {
                    foreach ($enrollments as $enrollment) {
                        CheckUserCourseCompletionJob::dispatch(
                            $enrollment->user_id,
                            $courseId
                        )->onQueue('completions');
                    }
                });
        });
    }
}