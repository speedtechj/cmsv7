<?php

namespace App\Filament\Appuser\Resources\PacklistitemResource\Pages;

use App\Filament\Appuser\Resources\PacklistitemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePacklistitem extends CreateRecord
{
    protected static string $resource = PacklistitemResource::class;
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
