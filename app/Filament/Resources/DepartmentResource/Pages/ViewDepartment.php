<?php

namespace App\Filament\Resources\DepartmentResource\Pages;

use App\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Department Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Department Name'),
                        Infolists\Components\TextEntry::make('code')
                            ->label('Department Code'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Events')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('events')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Event Title'),
                                Infolists\Components\TextEntry::make('category.name')
                                    ->label('Category'),
                                Infolists\Components\TextEntry::make('organizer.name')
                                    ->label('Organizer'),
                                Infolists\Components\TextEntry::make('venue')
                                    ->label('Venue'),
                                Infolists\Components\TextEntry::make('start_at')
                                    ->dateTime()
                                    ->label('Start Date'),
                                Infolists\Components\TextEntry::make('end_at')
                                    ->dateTime()
                                    ->label('End Date'),
                                Infolists\Components\TextEntry::make('capacity')
                                    ->label('Capacity'),
                                Infolists\Components\TextEntry::make('seats_left')
                                    ->label('Seats Left'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn ($state): string => $state->value ?? 'Unknown')
                                    ->color(fn ($state): string => match ($state?->value) {
                                        'draft' => 'gray',
                                        'published' => 'success',
                                        'cancelled' => 'danger',
                                        'completed' => 'warning',
                                        default => 'gray',
                                    }),
                            ])
                            ->columns(3)
                            ->contained(false),
                    ])
                    ->collapsible(),
            ]);
    }
}
