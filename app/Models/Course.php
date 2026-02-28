<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Course extends Model
{
    use SoftDeletes ,HasFactory;

    protected $fillable = [
        'level_id',
        'title',
        'slug',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // ── Boot: auto-generate unique slugs (withTrashed) ───────────────────────
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = static::generateUniqueSlug($course->title);
            }
        });

        static::updating(function (Course $course) {
            if ($course->isDirty('title') && ! $course->isDirty('slug')) {
                $course->slug = static::generateUniqueSlug($course->title, $course->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        // Check withTrashed so restored records don't collide
        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(CourseCompletion::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Completion % for a given user. Always queries live — never cached.
     * Returns 0 if no lessons exist to avoid division by zero.
     */
    public function completionPercentageFor(User $user): int
    {
        $total = $this->lessons()->count();

        if ($total === 0) {
            return 0;
        }

        $completed = LessonProgress::whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        return (int) round(($completed / $total) * 100);
    }
    public function certificates(): HasMany
{
    return $this->hasMany(Certificate::class);
}
}