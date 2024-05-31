<?php

namespace App\Filament\Monitoring\Resources\CarrierResource\Pages;

use App\Filament\Monitoring\Resources\CarrierResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCarrier extends CreateRecord
{
    protected static string $resource = CarrierResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
