<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Users';
    protected static ?int $navigationSort = 1;

    // Read-only — no create/edit pages
    public static function form(Form $form): Form
    {
        return $form->schema([]); // intentionally empty
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->width(60)
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean(),

                Tables\Columns\TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Enrolled Courses')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('courseCompletions_count')
                    ->counts('courseCompletions')
                    ->label('Completed')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('admins')
                    ->query(fn ($query) => $query->where('is_admin', true))
                    ->label('Admins Only'),
            ])
            ->actions([
                // View-only — clicking shows enrolled courses with progress
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelationManagers(): array
    {
        return [
            UserResource\RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // No create or edit — read-only resource
        ];
    }

    // Disable create button in the UI
    public static function canCreate(): bool
    {
        return false;
    }
}