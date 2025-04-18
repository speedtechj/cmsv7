<?php

namespace App\Filament\Appuser\Widgets;

use App\Models\Batch;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class PrevContainerChart extends ChartWidget
{
    protected static ?string $heading = 'Previous Year Container';
    protected static ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $data = Trend::model(Batch::class)
        ->between(
            start: now()->startOfYear()->subYear(1),
            end: now()->endOfYear()->subYear(1),
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
}
