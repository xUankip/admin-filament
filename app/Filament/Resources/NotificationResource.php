<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\UserNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = UserNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('channel')
                    ->options([
                        'in_app' => 'in_app',
                        'push' => 'push',
                        'email' => 'email',
                    ])->required(),
                Forms\Components\TextInput::make('type')->required(),
                Forms\Components\KeyValue::make('data')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('channel')->badge(),
                Tables\Columns\IconColumn::make('read_at')->boolean()->label('Read'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read_at')->label('Read'),
                Tables\Filters\SelectFilter::make('channel')->options([
                    'in_app' => 'in_app',
                    'push' => 'push',
                    'email' => 'email',
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
        ];
    }
}
