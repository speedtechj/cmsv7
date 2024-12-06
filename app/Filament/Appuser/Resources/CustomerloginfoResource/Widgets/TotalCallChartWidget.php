<?php

namespace App\Filament\Appuser\Resources\CustomerloginfoResource\Widgets;

use App\Models\Calllog;
use Flowframe\Trend\Trend;
use App\Models\Logcallsetting;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TotalCallChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Call Log Chart';
    protected static ?string $maxHeight = '200px';
        protected function getData(): array
    {
        $callid = Logcallsetting::where('is_active',1)->first() ?? 0;
        
        $data = Trend::query(
            Calllog::query()
                ->where('calltype_id', $callid->calltype_id)
                ->where('user_id', auth()->id())
        )
            ->between(
                start: $callid->start_date->startOfDay(),
                end: $callid->end_date->endOfDay(),
            )
            ->perDay()
            ->count();
       
        return [
           'datasets' => [
            [
                'label' => 'Total Calls Created',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
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
