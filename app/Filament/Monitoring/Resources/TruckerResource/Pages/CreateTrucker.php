<?php

namespace App\Filament\Monitoring\Resources\TruckerResource\Pages;

use App\Filament\Monitoring\Resources\TruckerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrucker extends CreateRecord
{
    protected static string $resource = TruckerResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
