<?php

namespace App\Filament\Monitoring\Widgets;

use App\Models\Batch;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class MonthlyContainerChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Container';
    protected static string $color = 'info';
    protected function getData(): array
    {
        $data = Trend::model(Batch::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Total Container',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => ['#90EE90','#013220'],
                'borderColor' => '#FFFFFF',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getOptions(): array
{
    return [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];
}
}
