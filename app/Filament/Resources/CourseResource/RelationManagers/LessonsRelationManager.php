<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';
    protected static ?string $title       = 'Lessons';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('video_url')
                ->label('Video URL')
                ->url()
                ->required()
                ->columnSpanFull(),

            TextInput::make('order')
                ->numeric()
                ->default(fn () => $this->getOwnerRecord()->lessons()->max('order') + 1)
                ->required(),

            TextInput::make('duration_seconds')
                ->label('Duration (seconds)')
                ->numeric(),

            Toggle::make('is_free_preview')
                ->label('Free Preview'),

            Textarea::make('description')
                ->rows(2)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')          // ← drag handle column, updates 'order' field
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->alignCenter()
                    ->width('50px'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('duration_seconds')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state
                        ? gmdate('G:i:s', $state)
                        : '—'
                    )
                    ->alignCenter(),

                IconColumn::make('is_free_preview')
                    ->label('Preview')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('progress_count')
                    ->label('Completions')
                    ->counts('progress')
                    ->alignCenter(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}