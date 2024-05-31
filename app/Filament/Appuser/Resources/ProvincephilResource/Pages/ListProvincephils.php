<?php

namespace App\Filament\Appuser\Resources\ProvincephilResource\Pages;

use App\Filament\Appuser\Resources\ProvincephilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProvincephils extends ListRecords
{
    protected static string $resource = ProvincephilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
