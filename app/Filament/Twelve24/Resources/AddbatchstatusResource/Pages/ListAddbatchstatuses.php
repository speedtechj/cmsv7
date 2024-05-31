<?php

namespace App\Filament\Twelve24\Resources\AddbatchstatusResource\Pages;

use App\Filament\Twelve24\Resources\AddbatchstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddbatchstatuses extends ListRecords
{
    protected static string $resource = AddbatchstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
