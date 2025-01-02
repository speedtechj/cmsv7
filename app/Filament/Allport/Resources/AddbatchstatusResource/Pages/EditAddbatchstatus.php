<?php

namespace App\Filament\Allport\Resources\AddbatchstatusResource\Pages;

use App\Filament\Allport\Resources\AddbatchstatusResource;
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
