<?php

namespace App\Filament\Twelve24\Resources\EditinvoicestatusResource\Pages;

use App\Filament\Twelve24\Resources\EditinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEditinvoicestatuses extends ListRecords
{
    protected static string $resource = EditinvoicestatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
