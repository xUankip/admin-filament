<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Department;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CertificatesByDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Certificates by Department';
    protected static ?string $maxHeight = '320px';
    protected int|string|array $columnSpan = [
        'default' => 3,
        'sm' => 1,
        'lg' => 3,
    ];

    protected function getData(): array
    {
        $rows = Certificate::query()
            ->select('events.department_id', DB::raw('COUNT(*) as count'))
            ->join('events', 'certificates.event_id', '=', 'events.id')
            ->groupBy('events.department_id')
            ->pluck('count', 'events.department_id')
            ->toArray();

        $labels = [];
        $data = [];
        foreach ($rows as $deptId => $count) {
            $labels[] = Department::find($deptId)->name ?? 'Unknown';
            $data[] = (int) $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Certificates',
                    'data' => $data,
                    'backgroundColor' => '#4f46e5',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => ['enabled' => true],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }
}


