<?php

namespace App\Filament\Resources\FreightResource\Pages;

use App\Filament\Resources\FreightResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFreight extends CreateRecord
{
    protected static string $resource = FreightResource::class;
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
