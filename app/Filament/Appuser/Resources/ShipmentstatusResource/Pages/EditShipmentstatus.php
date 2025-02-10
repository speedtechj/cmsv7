<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\Pages;

use App\Filament\Appuser\Resources\ShipmentstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShipmentstatus extends EditRecord
{
    protected static string $resource = ShipmentstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
