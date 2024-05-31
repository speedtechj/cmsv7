<?php

namespace App\Filament\Appuser\Resources\CitycanResource\Pages;

use App\Filament\Appuser\Resources\CitycanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCitycans extends ListRecords
{
    protected static string $resource = CitycanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
