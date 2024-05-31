<?php

namespace App\Filament\Monitoring\Resources\CarrierResource\Pages;

use App\Filament\Monitoring\Resources\CarrierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarrier extends EditRecord
{
    protected static string $resource = CarrierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
