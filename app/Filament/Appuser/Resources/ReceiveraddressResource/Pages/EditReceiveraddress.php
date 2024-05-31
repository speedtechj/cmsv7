<?php

namespace App\Filament\Appuser\Resources\ReceiveraddressResource\Pages;

use App\Filament\Appuser\Resources\ReceiveraddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiveraddress extends EditRecord
{
    protected static string $resource = ReceiveraddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
