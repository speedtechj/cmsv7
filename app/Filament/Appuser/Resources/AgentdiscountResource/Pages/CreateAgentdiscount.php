<?php

namespace App\Filament\Appuser\Resources\AgentdiscountResource\Pages;

use App\Filament\Appuser\Resources\AgentdiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgentdiscount extends CreateRecord
{
    protected static string $resource = AgentdiscountResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;

    }
}
