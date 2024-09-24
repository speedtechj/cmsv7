<?php

namespace App\Filament\Appuser\Resources\LogsettingResource\Pages;

use App\Filament\Appuser\Resources\LogsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLogsetting extends CreateRecord
{
    protected static bool $canCreateAnother = false;
    protected static string $resource = LogsettingResource::class;
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
