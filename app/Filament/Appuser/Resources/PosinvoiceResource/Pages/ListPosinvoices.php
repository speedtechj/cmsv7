<?php

namespace App\Filament\Appuser\Resources\PosinvoiceResource\Pages;

use App\Filament\Appuser\Resources\PosinvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosinvoices extends ListRecords
{
    protected static string $resource = PosinvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
