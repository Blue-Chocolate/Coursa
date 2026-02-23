<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Course;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title = 'Enrolled Courses';

    public function form(Form $form): Form
    {
        return $form->schema([]); // read-only
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable(),

                Tables\Columns\TextColumn::make('course.level.name')
                    ->label('Level')
                    ->badge(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                // Completion % — computed per row
                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('Progress')
                    ->getStateUsing(function ($record): string {
                        $user   = $record->user;
                        $course = $record->course;
                        return $course->completionPercentageFor($user) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int) $state === 100 => 'success',
                        (int) $state >= 50   => 'warning',
                        default              => 'gray',
                    }),

                Tables\Columns\IconColumn::make('completed')
                    ->label('Completed')
                    ->getStateUsing(fn ($record) => $record->user->hasCompletedCourse($record->course_id))
                    ->boolean(),
            ]);
    }
}