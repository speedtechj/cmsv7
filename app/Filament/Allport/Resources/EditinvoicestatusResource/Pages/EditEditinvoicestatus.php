<?php

namespace App\Filament\Allport\Resources\EditinvoicestatusResource\Pages;

use App\Filament\Allport\Resources\EditinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEditinvoicestatus extends EditRecord
{
    protected static string $resource = EditinvoicestatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
