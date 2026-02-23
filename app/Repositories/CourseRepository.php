<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function allPublished(array $filters = []): LengthAwarePaginator
    {
        return Course::published()
            ->with(['level'])                           // avoid N+1 on level name
            ->withCount('lessons')                      // show lesson count in listing
            ->when(
                isset($filters['level_id']),
                fn ($q) => $q->where('level_id', $filters['level_id'])
            )
            ->when(
                isset($filters['search']),
                fn ($q) => $q->where('title', 'like', '%' . $filters['search'] . '%')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    }

    public function findBySlug(string $slug): ?Course
    {
        return Course::published()
            ->with([
                'level',
                'lessons' => fn ($q) => $q->orderBy('order'), // always ordered
            ])
            ->where('slug', $slug)
            ->first();
    }

    public function enrolledByUser(int $userId): LengthAwarePaginator
    {
        return Course::published()
            ->with(['level'])
            ->withCount('lessons')
            ->whereHas('enrollments', fn ($q) => $q->where('user_id', $userId))
            ->paginate(12);
    }
}