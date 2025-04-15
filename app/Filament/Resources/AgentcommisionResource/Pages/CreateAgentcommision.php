<?php

namespace App\Filament\Resources\AgentcommisionResource\Pages;

use App\Filament\Resources\AgentcommisionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgentcommision extends CreateRecord
{
    protected static string $resource = AgentcommisionResource::class;
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
