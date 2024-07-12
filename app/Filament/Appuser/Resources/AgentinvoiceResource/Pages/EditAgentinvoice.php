<?php

namespace App\Filament\Appuser\Resources\AgentinvoiceResource\Pages;

use App\Filament\Appuser\Resources\AgentinvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentinvoice extends EditRecord
{
    protected static string $resource = AgentinvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
