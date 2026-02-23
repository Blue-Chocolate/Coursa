<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Users';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]); // read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.level.name')
                    ->label('Level')
                    ->badge(),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completion_percentage')
                    ->label('Progress')
                    ->getStateUsing(function (Enrollment $record): string {
                        return $record->course->completionPercentageFor($record->user) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int) $state === 100 => 'success',
                        (int) $state >= 50   => 'warning',
                        default              => 'gray',
                    }),

                Tables\Columns\IconColumn::make('course_completed')
                    ->label('Finished')
                    ->getStateUsing(fn (Enrollment $record) =>
                        $record->user->hasCompletedCourse($record->course_id)
                    )
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),

                Tables\Filters\Filter::make('completed')
                    ->label('Completed Only')
                    ->query(fn ($query) =>
                        $query->whereHas('user.courseCompletions', fn ($q) =>
                            $q->whereColumn('course_id', 'enrollments.course_id')
                        )
                    ),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
        ];
    }
}