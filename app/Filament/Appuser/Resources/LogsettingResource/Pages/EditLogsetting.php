<?php

namespace App\Filament\Appuser\Resources\LogsettingResource\Pages;

use App\Filament\Appuser\Resources\LogsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogsetting extends EditRecord
{
    protected static string $resource = LogsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
