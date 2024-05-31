<?php

namespace App\Filament\Appuser\Resources\StatuscategoryResource\Pages;

use App\Filament\Appuser\Resources\StatuscategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStatuscategory extends CreateRecord
{
    protected static string $resource = StatuscategoryResource::class;
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
