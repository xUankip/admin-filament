<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_id')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('uploader_id')
                    ->relationship('uploader', 'name')
                    ->default(fn() => auth()->id())
                    ->searchable()
                    ->preload(),

                Forms\Components\Section::make('Upload / Link')
                    ->schema([
                        Forms\Components\FileUpload::make('image_file')
                            ->label('Image')
                            ->image()
                            ->directory('media')
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1280')
                            ->imageResizeTargetHeight('960')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('url', \Storage::url($state));
                                    $set('type', 'image/jpeg');
                                }
                            }),
                        Forms\Components\TextInput::make('url')
                            ->label('URL (optional)')
                            ->url()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('type')
                            ->label('MIME (auto for uploads)')
                            ->maxLength(64),
                    ])->columns(2),

                Forms\Components\TagsInput::make('tags')->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('preview')
                    ->label('Media')
                    ->view('filament.tables.columns.media_cell')
                    ->sortable(query: function ($query, string $direction) {
                        $query->orderBy('created_at', $direction);
                    }),
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Uploader')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'image',
                        'warning' => 'video',
                        'success' => 'audio',
                    ])
                    ->searchable(),
                Tables\Columns\TagsColumn::make('tags')
                    ->separator(',')
                    ->limit(3)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn() => Media::query()->select('type')->distinct()->pluck('type', 'type')->toArray()),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
            'view' => Pages\ViewMedia::route('/{record}'),
        ];
    }
}


