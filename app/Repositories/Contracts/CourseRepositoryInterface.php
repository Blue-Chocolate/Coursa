<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

interface CourseRepositoryInterface
{
    public function allPublished(array $filters = []): LengthAwarePaginator;
    public function findBySlug(string $slug): ?Course;
    public function enrolledByUser(int $userId): LengthAwarePaginator;
}