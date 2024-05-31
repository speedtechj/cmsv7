<?php

namespace App\Filament\Resources\ZonepriceResource\Pages;

use App\Filament\Resources\ZonepriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZoneprices extends ListRecords
{
    protected static string $resource = ZonepriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
