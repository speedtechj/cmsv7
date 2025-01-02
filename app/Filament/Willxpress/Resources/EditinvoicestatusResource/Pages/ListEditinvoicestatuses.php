<?php

namespace App\Filament\Willxpress\Resources\EditinvoicestatusResource\Pages;

use App\Filament\Willxpress\Resources\EditinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEditinvoicestatuses extends ListRecords
{
    protected static string $resource = EditinvoicestatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
