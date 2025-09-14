<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                Forms\Components\Select::make('department_id')->relationship('department', 'name')->searchable()->preload(),
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
                Forms\Components\Select::make('organizer_id')->relationship('organizer', 'name')->searchable()->preload(),
                Forms\Components\TextInput::make('venue')->maxLength(255),
                Forms\Components\DateTimePicker::make('start_at')->required(),
                Forms\Components\DateTimePicker::make('end_at')->required(),
                Forms\Components\TextInput::make('capacity')->numeric()->minValue(0)->required(),
                Forms\Components\TextInput::make('seats_left')->numeric()->minValue(0)->required(),
                Forms\Components\Toggle::make('waitlist_enabled')->label('Enable waitlist'),
                Forms\Components\Select::make('status')->options([
                    'draft' => 'Draft',
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'published' => 'Published',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('department.name')->label('Department')->toggleable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->toggleable(),
                Tables\Columns\TextColumn::make('organizer.name')->label('Organizer')->toggleable(),
                Tables\Columns\TextColumn::make('start_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('end_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('capacity'),
                Tables\Columns\TextColumn::make('seats_left')->badge()->color(fn($state) => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\IconColumn::make('waitlist_enabled')->boolean(),

                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registrations')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('certificates_count')
                    ->counts('certificates')
                    ->label('Certificates')
                    ->badge()
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')->colors([
                    'gray' => 'draft',
                    'warning' => 'pending',
                    'info' => 'approved',
                    'success' => 'published',
                    'primary' => 'completed',
                    'danger' => 'canceled',
                ]),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('waitlist_enabled'),
                Tables\Filters\SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'published' => 'Published',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ]),
                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RegistrationsRelationManager::class,
            RelationManagers\CertificatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
}
