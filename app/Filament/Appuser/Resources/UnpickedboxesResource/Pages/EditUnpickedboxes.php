<?php

namespace App\Filament\Appuser\Resources\UnpickedboxesResource\Pages;

use App\Filament\Appuser\Resources\UnpickedboxesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnpickedboxes extends EditRecord
{
    protected static string $resource = UnpickedboxesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
