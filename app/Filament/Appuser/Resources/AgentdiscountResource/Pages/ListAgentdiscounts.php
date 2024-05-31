<?php

namespace App\Filament\Appuser\Resources\AgentdiscountResource\Pages;

use App\Filament\Appuser\Resources\AgentdiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentdiscounts extends ListRecords
{
    protected static string $resource = AgentdiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
