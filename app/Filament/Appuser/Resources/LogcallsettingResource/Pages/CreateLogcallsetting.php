<?php

namespace App\Filament\Appuser\Resources\LogcallsettingResource\Pages;

use App\Filament\Appuser\Resources\LogcallsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogcallsetting extends CreateRecord
{
    protected static string $resource = LogcallsettingResource::class;
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
