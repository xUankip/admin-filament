<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('registration_id')
                    ->relationship('registration', 'id')
                    ->searchable()
                    ->preload()
                    ->label('Registration')
                    ->required(),
                Forms\Components\DateTimePicker::make('checked_in_at')->seconds(false),
                Forms\Components\DateTimePicker::make('checked_out_at')->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration.event.title')->label('Event')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('registration.user.name')->label('User')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('checked_in_at')->dateTime()->badge()->color('success'),
                Tables\Columns\TextColumn::make('checked_out_at')->dateTime()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListAttendance::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
        ];
    }
}


