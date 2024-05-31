<?php

namespace App\Filament\Appuser\Resources\AgentpriceResource\Pages;

use App\Filament\Appuser\Resources\AgentpriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentprices extends ListRecords
{
    protected static string $resource = AgentpriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
