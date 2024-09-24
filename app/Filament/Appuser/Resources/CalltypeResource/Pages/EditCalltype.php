<?php

namespace App\Filament\Appuser\Resources\CalltypeResource\Pages;

use App\Filament\Appuser\Resources\CalltypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalltype extends EditRecord
{
    protected static string $resource = CalltypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
