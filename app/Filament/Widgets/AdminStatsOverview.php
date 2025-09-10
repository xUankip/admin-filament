<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AdminStatsOverview extends BaseStatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Users', (string) User::count())
                ->description('Total registered users')
                ->icon('heroicon-o-user-group')
                ->color('primary'),
            Card::make('Events', (string) Event::count())
                ->description('Total events')
                ->icon('heroicon-o-calendar')
                ->color('success'),
            Card::make('Registrations', (string) Registration::count())
                ->description('Total registrations')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('warning'),
        ];
    }
}


