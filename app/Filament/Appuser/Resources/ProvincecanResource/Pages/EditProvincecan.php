<?php

namespace App\Filament\Appuser\Resources\ProvincecanResource\Pages;

use App\Filament\Appuser\Resources\ProvincecanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvincecan extends EditRecord
{
    protected static string $resource = ProvincecanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
