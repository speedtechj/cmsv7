<?php

namespace App\Filament\Willxpress\Resources\EditinvoicestatusResource\Pages;

use App\Filament\Willxpress\Resources\EditinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEditinvoicestatus extends EditRecord
{
    protected static string $resource = EditinvoicestatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
