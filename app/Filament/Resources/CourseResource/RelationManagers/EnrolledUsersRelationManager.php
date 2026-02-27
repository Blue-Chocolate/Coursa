<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\LessonProgress;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrolledUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title       = 'Enrolled Students';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Eager load user — prevents N+1 on user.name/email columns
                $query->with('user');
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('completion_pct')
                    ->label('Progress')
                    ->getStateUsing(function ($record): string {
                        // Scoped per-user per-course: no data leaks
                        $course    = $this->getOwnerRecord();
                        $lessonIds = $course->lessons->pluck('id');
                        $total     = $lessonIds->count();

                        if ($total === 0) return '0%';

                        $completed = LessonProgress::where('user_id', $record->user_id)
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

                TextColumn::make('completed')
                    ->label('Course Done')
                    ->getStateUsing(fn ($record): string =>
                        $record->user->hasCompletedCourse($this->getOwnerRecord()->id)
                            ? '✓ Yes' : '—'
                    )
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === '✓ Yes' ? 'success' : 'gray'
                    ),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }
}
