<?php

namespace App\Filament\Appuser\Resources\UnpickedboxesResource\Widgets;

use App\Models\Booking;
use App\Models\Unpickedboxes;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Unpickedbox extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Box Not Pickup', Unpickedboxes::where('is_pickup',false)->count())
            ->color('primary'),
            Stat::make('Total Box Not Paid', Unpickedboxes::where('is_paid',false)->count()),
        ];
    }
}
