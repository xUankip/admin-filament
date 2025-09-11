<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AttendanceTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Attendance Over Time';
    protected static ?string $maxHeight = '320px';
    protected int|string|array $columnSpan = [
        'default' => 3,
        'sm' => 1,
        'lg' => 3,
    ];
    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();

        $rows = Attendance::query()
            ->select(DB::raw('DATE(checked_in_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereNotNull('checked_in_at')
            ->where('checked_in_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = $day;
            $data[] = (int)($rows[$day] ?? 0);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Attendance',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'tension' => 0.3,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top'],
                'tooltip' => ['enabled' => true],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                    'grid' => ['display' => true],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}


