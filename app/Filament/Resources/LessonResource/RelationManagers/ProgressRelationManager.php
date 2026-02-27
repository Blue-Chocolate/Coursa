<?php 
namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'progress';
    protected static ?string $title       = 'Student Progress';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('watch_seconds')
                    ->label('Watch Time')
                    ->formatStateUsing(fn ($state) => gmdate('G:i:s', $state ?? 0))
                    ->sortable(),

                IconColumn::make('completed_at')
                    ->label('Completed')
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! is_null($record->completed_at))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not completed'),
            ])
            ->defaultSort('completed_at', 'desc');
    }
}

