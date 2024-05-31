<?php

namespace App\Filament\Monitoring\Resources\CarrierResource\Pages;

use App\Filament\Monitoring\Resources\CarrierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarriers extends ListRecords
{
    protected static string $resource = CarrierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
