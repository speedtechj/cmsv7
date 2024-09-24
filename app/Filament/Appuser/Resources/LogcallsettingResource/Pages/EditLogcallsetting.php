<?php

namespace App\Filament\Appuser\Resources\LogcallsettingResource\Pages;

use App\Filament\Appuser\Resources\LogcallsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogcallsetting extends EditRecord
{
    protected static string $resource = LogcallsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
