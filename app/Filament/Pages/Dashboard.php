<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Widgets\AdminStatsOverview;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|string|array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }
}
