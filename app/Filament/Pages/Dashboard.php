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
use App\Filament\Widgets\RegistrationsTrendChart;
use App\Filament\Widgets\EventsStatusPieChart;
use App\Filament\Widgets\CertificatesByDepartmentChart;
use App\Filament\Widgets\AttendanceTrendChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ];
    }
    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            RegistrationsTrendChart::class,
            EventsStatusPieChart::class,
            AttendanceTrendChart::class,

//            CertificatesByDepartmentChart::class,
        ];
    }
}
