<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use App\Models\Enrollment;

class EnrolledUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $title = 'Enrolled Users';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('Progress')
                    ->getStateUsing(function (Enrollment $record): string {
                        // $this->getOwnerRecord() is the Course
                        return $record->course->completionPercentageFor($record->user) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int) $state === 100 => 'success',
                        (int) $state >= 50   => 'warning',
                        default              => 'gray',
                    }),

                Tables\Columns\IconColumn::make('completed')
                    ->getStateUsing(fn (Enrollment $record) =>
                        $record->user->hasCompletedCourse($record->course_id)
                    )
                    ->boolean(),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }
}