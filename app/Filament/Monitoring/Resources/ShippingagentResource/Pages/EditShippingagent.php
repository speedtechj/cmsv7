<?php

namespace App\Filament\Monitoring\Resources\ShippingagentResource\Pages;

use App\Filament\Monitoring\Resources\ShippingagentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingagent extends EditRecord
{
    protected static string $resource = ShippingagentResource::class;

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
