<?php

namespace App\Filament\Appuser\Resources\ZonerouteResource\Pages;

use App\Filament\Appuser\Resources\ZonerouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZoneroutes extends ListRecords
{
    protected static string $resource = ZonerouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
