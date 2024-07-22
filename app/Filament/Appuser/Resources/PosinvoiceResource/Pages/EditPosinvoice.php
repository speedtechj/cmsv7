<?php

namespace App\Filament\Appuser\Resources\PosinvoiceResource\Pages;

use App\Filament\Appuser\Resources\PosinvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosinvoice extends EditRecord
{
    protected static string $resource = PosinvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
