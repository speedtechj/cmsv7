<?php

namespace App\Filament\Monitoring\Resources\ShippingagentResource\Pages;

use App\Filament\Monitoring\Resources\ShippingagentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingagent extends CreateRecord
{
    protected static string $resource = ShippingagentResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
