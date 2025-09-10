<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Widgets;

class StatsOverviewWidget extends Widgets\StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Widgets\StatsOverviewWidget\Card::make('Users', (string)User::count())
                ->description('Total registered users')
                ->icon('heroicon-o-user-group')
                ->color('primary'),
            Widgets\StatsOverviewWidget\Card::make('Events', (string)Event::count())
                ->description('Total events')
                ->icon('heroicon-o-calendar')
                ->color('success'),
            Widgets\StatsOverviewWidget\Card::make('Registrations', (string)Registration::count())
                ->description('Total registrations')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('warning'),
        ];
    }
}
