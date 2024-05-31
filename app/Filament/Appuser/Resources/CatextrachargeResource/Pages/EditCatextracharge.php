<?php

namespace App\Filament\Appuser\Resources\CatextrachargeResource\Pages;

use App\Filament\Appuser\Resources\CatextrachargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatextracharge extends EditRecord
{
    protected static string $resource = CatextrachargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
