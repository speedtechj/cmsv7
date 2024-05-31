<?php

namespace App\Filament\Resources\AddbatchstatusResource\Pages;

use App\Filament\Resources\AddbatchstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddbatchstatus extends EditRecord
{
    protected static string $resource = AddbatchstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
