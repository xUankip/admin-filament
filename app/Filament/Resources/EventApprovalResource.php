<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventApprovalResource\Pages;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventApprovalResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    protected static ?string $navigationGroup = 'Approvals';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('organizer.name')->label('Organizer'),
                Tables\Columns\TextColumn::make('start_at')->dateTime(),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'published' => 'published',
                ])->default('pending'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(fn($record) => $record->update(['status' => 'approved']))
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check'),
                Tables\Actions\Action::make('publish')
                    ->visible(fn($record) => in_array($record->status, ['approved']))
                    ->action(fn($record) => $record->update(['status' => 'published']))
                    ->label('Publish')
                    ->color('primary')
                    ->icon('heroicon-o-bolt'),
                Tables\Actions\Action::make('reject')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(fn($record) => $record->update(['status' => 'canceled']))
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventApprovals::route('/'),
        ];
    }
}
