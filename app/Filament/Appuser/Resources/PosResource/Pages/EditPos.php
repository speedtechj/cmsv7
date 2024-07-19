<?php

namespace App\Filament\Appuser\Resources\PosResource\Pages;

use App\Filament\Appuser\Resources\PosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPos extends EditRecord
{
    protected static string $resource = PosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
