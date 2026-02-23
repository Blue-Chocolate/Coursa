<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';
    protected static ?string $title = 'Lessons';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpan(2),

            Forms\Components\TextInput::make('video_url')
                ->label('Video URL')
                ->url()
                ->required()
                ->placeholder('https://www.youtube.com/watch?v=...')
                ->columnSpan(2),

            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(fn () => $this->getOwnerRecord()->lessons()->max('order') + 1)
                ->required(),

            Forms\Components\TextInput::make('duration_seconds')
                ->label('Duration (seconds)')
                ->numeric()
                ->nullable(),

            Forms\Components\Toggle::make('is_free_preview')
                ->label('Free Preview')
                ->helperText('Guests can watch this lesson without enrolling')
                ->columnSpan(2),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('order')
            ->reorderable('order') // drag-and-drop reorder
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->width(60)
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->formatStateUsing(function (?int $state): string {
                        if (! $state) return '—';
                        $m = intdiv($state, 60);
                        $s = $state % 60;
                        return "{$m}m {$s}s";
                    }),

                Tables\Columns\IconColumn::make('is_free_preview')
                    ->label('Free Preview')
                    ->boolean(),

                Tables\Columns\TextColumn::make('progress_count')
                    ->counts('progress')
                    ->label('Started By')
                    ->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}