<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Content Management';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')->label('Event')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status'),
                Tables\Columns\IconColumn::make('on_waitlist')->boolean(),
                Tables\Columns\IconColumn::make('fee_paid')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('on_waitlist'),
                Tables\Filters\TernaryFilter::make('fee_paid'),
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'pending',
                    'confirmed' => 'confirmed',
                    'canceled' => 'canceled',
                    'no-show' => 'no-show',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Fee Paid')
                    ->visible(fn($record) => ! $record->fee_paid)
                    ->action(fn($record) => $record->update(['fee_paid' => true]))
                    ->color('success')
                    ->icon('heroicon-o-banknotes'),
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
            'index' => Pages\ListRegistrations::route('/'),
        ];
    }
}


