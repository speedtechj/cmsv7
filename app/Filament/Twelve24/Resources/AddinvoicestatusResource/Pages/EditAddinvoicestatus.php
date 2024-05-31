<?php

namespace App\Filament\Twelve24\Resources\AddinvoicestatusResource\Pages;

use App\Filament\Twelve24\Resources\AddinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddinvoicestatus extends EditRecord
{
    protected static string $resource = AddinvoicestatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
