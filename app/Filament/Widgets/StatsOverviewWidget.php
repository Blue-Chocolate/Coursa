<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalCourses     = Course::published()->count();
        $totalEnrollments = Enrollment::count();

        // Avg completion % across all enrollments
        // We compute it in PHP to reuse completionPercentageFor()
        // For large datasets you'd push this to a dedicated query
        $avgCompletion = $this->getAverageCompletion();

        return [
            Stat::make('Published Courses', $totalCourses)
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),

            Stat::make('Total Enrollments', $totalEnrollments)
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Avg Completion', $avgCompletion . '%')
                ->icon('heroicon-o-chart-bar')
                ->color('success'),

            Stat::make('Completions', CourseCompletion::count())
                ->icon('heroicon-o-trophy')
                ->color('warning'),
        ];
    }

    private function getAverageCompletion(): int
    {
        // Efficient: single query approach
        // total completed lessons / total (lessons × enrollments) across all courses
        $enrollments = Enrollment::with(['course.lessons', 'user'])->get();

        if ($enrollments->isEmpty()) {
            return 0;
        }

        $total = $enrollments->sum(fn ($e) =>
            $e->course->completionPercentageFor($e->user)
        );

        return (int) round($total / $enrollments->count());
    }
}