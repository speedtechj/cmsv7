<?php

namespace App\Filament\Resources\AgentcommisionResource\Pages;

use App\Filament\Resources\AgentcommisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentcommisions extends ListRecords
{
    protected static string $resource = AgentcommisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
