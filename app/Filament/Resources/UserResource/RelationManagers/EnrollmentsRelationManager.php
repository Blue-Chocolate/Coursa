<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\LessonProgress;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title       = 'Enrolled Courses';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Eager load course + lessons in one query — kills N+1
                $query->with(['course.lessons', 'course.level']);
            })
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('course.level.name')
                    ->label('Level')
                    ->badge(),

                TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('completion_pct')
                    ->label('Progress')
                    ->getStateUsing(function ($record): string {
                        // Scoped to THIS user + THIS course only — no cross-user leaks
                        $userId    = $this->getOwnerRecord()->id;
                        $lessonIds = $record->course->lessons->pluck('id');
                        $total     = $lessonIds->count();

                        if ($total === 0) return '0%';

                        $completed = LessonProgress::where('user_id', $userId)
                            ->whereIn('lesson_id', $lessonIds)
                            ->whereNotNull('completed_at')
                            ->count();

                        return round(($completed / $total) * 100) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int) $state === 100 => 'success',
                        (int) $state >= 50   => 'warning',
                        default              => 'gray',
                    }),

                TextColumn::make('course_completed')
                    ->label('Completed')
                    ->getStateUsing(fn ($record): string =>
                        $this->getOwnerRecord()->hasCompletedCourse($record->course_id)
                            ? '✓ Done' : '—'
                    )
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === '✓ Done' ? 'success' : 'gray'
                    ),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }
}