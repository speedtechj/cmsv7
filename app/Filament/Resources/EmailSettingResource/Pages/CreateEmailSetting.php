<?php

namespace App\Filament\Resources\EmailSettingResource\Pages;

use App\Filament\Resources\EmailSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailSetting extends CreateRecord
{
    protected static string $resource = EmailSettingResource::class;
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
