<?php

namespace App\Filament\Appuser\Resources\ZonerouteResource\Pages;

use App\Filament\Appuser\Resources\ZonerouteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZoneroute extends CreateRecord
{
    protected static string $resource = ZonerouteResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
       
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
