<?php

namespace App\Filament\Monitoring\Widgets;

use App\Models\Batch;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TotalContainerChart extends ChartWidget
{
    protected static ?string $heading = ' Year Total Container';
    
    protected function getData(): array        
    {
      
        $data = Trend::model(Batch::class)
        ->between(
            start: now()->startOfYear()->subYears(6),
            end: now()->endOfYear(),
        )
        ->perYear()
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
public function getDescription(): ?string
{
    return 'Number of Containers';
}
// protected function getFilters(): ?array
// {
//     return [
//         'today' => 'Today',
//         'week' => 'Last week',
//         'month' => 'Last month',
//         'year' => 'This year',
//     ];
// }

}
