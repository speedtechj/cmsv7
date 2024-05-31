<?php

namespace App\Filament\Monitoring\Resources\TruckerResource\Pages;

use App\Filament\Monitoring\Resources\TruckerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrucker extends EditRecord
{
    protected static string $resource = TruckerResource::class;

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
