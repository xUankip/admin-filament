<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RegistrationsTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Registrations Over Time';
    protected static ?string $maxHeight = '555px';

    protected int|string|array $columnSpan = [
        'default' => 2,
        'sm' => 1,
        'lg' => 2,
    ];

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(29)->startOfDay();

        $rows = Registration::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $startDate)
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
                    'label' => 'Registrations',
                    'data' => $data,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
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
            'color' => '#000000',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'family' => 'Inter, sans-serif',
                            'size' => 12,
                            'weight' => '600',
                        ],
                    ],
                ],
                'tooltip' => ['enabled' => true],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => ['display' => true],
                ],
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }

    protected function getMaxHeight(): ?string
    {
        return '560px';
    }
}
