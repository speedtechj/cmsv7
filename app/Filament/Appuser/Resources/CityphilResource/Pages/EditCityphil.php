<?php

namespace App\Filament\Appuser\Resources\CityphilResource\Pages;

use App\Filament\Appuser\Resources\CityphilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityphil extends EditRecord
{
    protected static string $resource = CityphilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
