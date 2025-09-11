<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EventsStatusPieChart extends ChartWidget
{
    protected static ?string $heading = 'Events by Status';
    protected static ?string $maxHeight = '280px';
    protected int|string|array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $rows = Event::query()
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = array_map(static fn($s) => ucfirst((string) $s), array_keys($rows));
        $data = array_values($rows);

        $colors = ['#22c55e', '#f59e0b', '#ef4444', '#6366f1', '#8b5cf6', '#06b6d4'];
        while (count($colors) < count($data)) {
            $colors = array_merge($colors, $colors);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                    'hoverBorderWidth' => 3,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'cutout' => '60%',
            'radius' => '80%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 12
                        ]
                    ]
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#ffffff',
                    'bodyColor' => '#ffffff',
                    'borderColor' => 'rgba(255, 255, 255, 0.2)',
                    'borderWidth' => 1,
                ],
            ],
            'elements' => [
                'arc' => [
                    'borderWidth' => 2,
                ]
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
