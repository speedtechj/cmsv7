<?php

namespace App\Filament\Resources\FreightResource\Pages;

use App\Filament\Resources\FreightResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFreights extends ListRecords
{
    protected static string $resource = FreightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
