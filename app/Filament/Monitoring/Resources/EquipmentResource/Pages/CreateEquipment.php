<?php

namespace App\Filament\Monitoring\Resources\EquipmentResource\Pages;

use App\Filament\Monitoring\Resources\EquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipment extends CreateRecord
{
    protected static string $resource = EquipmentResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
