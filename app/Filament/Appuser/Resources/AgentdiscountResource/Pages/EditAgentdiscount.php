<?php

namespace App\Filament\Appuser\Resources\AgentdiscountResource\Pages;

use App\Filament\Appuser\Resources\AgentdiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentdiscount extends EditRecord
{
    protected static string $resource = AgentdiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
