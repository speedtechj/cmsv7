<?php

namespace App\Filament\Appuser\Resources\CatextrachargeResource\Pages;

use App\Filament\Appuser\Resources\CatextrachargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatextracharges extends ListRecords
{
    protected static string $resource = CatextrachargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
