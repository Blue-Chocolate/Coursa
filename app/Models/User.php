<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function courseCompletions(): HasMany
    {
        return $this->hasMany(CourseCompletion::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function isEnrolledIn(int $courseId): bool
    {
        // Cache per-request to avoid N+1 on lesson lists
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->exists();
    }

    public function hasCompletedCourse(int $courseId): bool
    {
        return $this->courseCompletions()
            ->where('course_id', $courseId)
            ->exists();
    }

    public function progressForLesson(int $lessonId): ?LessonProgress
    {
        return $this->lessonProgress()
            ->where('lesson_id', $lessonId)
            ->first();
    }
        public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }
}