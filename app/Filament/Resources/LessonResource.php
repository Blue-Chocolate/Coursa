<?php
// ============================================================
// FILE 1: app/Filament/Resources/LessonResource.php
// ============================================================

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Course;
use App\Models\Lesson;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model           = Lesson::class;
    protected static ?string $navigationIcon  = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('course_id')
                ->label('Course')
                ->relationship('course', 'title')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            TextInput::make('video_url')
                ->label('Video URL')
                ->url()
                ->required()
                ->columnSpanFull()
                ->placeholder('https://youtube.com/watch?v=... or Vimeo or direct URL'),

            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->required(),

            TextInput::make('duration_seconds')
                ->label('Duration (seconds)')
                ->numeric()
                ->placeholder('e.g. 600 for 10 minutes'),

            Toggle::make('is_free_preview')
                ->label('Free Preview')
                ->helperText('Allow non-enrolled users to watch this lesson'),

            Textarea::make('description')
                ->rows(3)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('order')
                    ->sortable()
                    ->alignCenter(),

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
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('course_id')
            ->filters([
                SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_free_preview')
                    ->label('Preview')
                    ->options([
                        '1' => 'Free Preview',
                        '0' => 'Paid Only',
                    ]),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getRelationManagers(): array
    {
        return [
            LessonResource\RelationManagers\ProgressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit'   => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}

