<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{   use HasFactory;
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
}