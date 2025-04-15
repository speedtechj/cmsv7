<?php

namespace App\Filament\Resources\AgentcommisionResource\Pages;

use App\Filament\Resources\AgentcommisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgentcommision extends EditRecord
{
    protected static string $resource = AgentcommisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
