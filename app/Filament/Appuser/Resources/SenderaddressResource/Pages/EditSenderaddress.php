<?php

namespace App\Filament\Appuser\Resources\SenderaddressResource\Pages;

use App\Filament\Appuser\Resources\SenderaddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSenderaddress extends EditRecord
{
    protected static string $resource = SenderaddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
