<?php

namespace App\Filament\Resources\ZoneSummaryResource\Pages;

use App\Models\Zone;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ZoneSummaryResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\ZoneSummaryResource\Widgets\ZoneBoxOverview;

class ListZoneSummaries extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ZoneSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            ZoneBoxOverview::class,
           
        ];
    }
   
}
