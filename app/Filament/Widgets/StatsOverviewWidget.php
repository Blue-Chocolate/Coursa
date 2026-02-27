<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Published Courses', Course::published()->count())
                ->description(Course::count() . ' total (incl. drafts)')
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),

            Stat::make('Total Enrollments', Enrollment::count())
                ->description(User::count() . ' registered users')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Avg Completion', $this->avgCompletion() . '%')
                ->description('Across all active enrollments')
                ->icon('heroicon-o-chart-bar')
                ->color('success'),

            Stat::make('Course Completions', CourseCompletion::count())
                ->description('Full course completions')
                ->icon('heroicon-o-trophy')
                ->color('warning'),

            Stat::make('Lessons Completed', LessonProgress::whereNotNull('completed_at')->count())
                ->description('Individual lesson completions')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Active Learners', $this->activeLearners())
                ->description('Users with at least 1 enrollment')
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }

    private function avgCompletion(): int
    {
        // Single efficient query:
        // For each enrollment, count completed lessons / total lessons in that course
        // Uses a subquery instead of loading everything into PHP
        $result = DB::select("
            SELECT ROUND(AVG(pct), 0) as avg_pct
            FROM (
                SELECT
                    e.id,
                    CASE
                        WHEN total.cnt = 0 THEN 0
                        ELSE ROUND((done.cnt / total.cnt) * 100, 0)
                    END as pct
                FROM enrollments e
                JOIN (
                    SELECT course_id, COUNT(*) as cnt
                    FROM lessons
                    GROUP BY course_id
                ) total ON total.course_id = e.course_id
                LEFT JOIN (
                    SELECT lp.user_id, l.course_id, COUNT(*) as cnt
                    FROM lesson_progress lp
                    JOIN lessons l ON l.id = lp.lesson_id
                    WHERE lp.completed_at IS NOT NULL
                    GROUP BY lp.user_id, l.course_id
                ) done ON done.user_id = e.user_id AND done.course_id = e.course_id
            ) sub
        ");

        return (int) ($result[0]->avg_pct ?? 0);
    }

    private function activeLearners(): int
    {
        return Enrollment::distinct('user_id')->count('user_id');
    }
}