<?php

namespace App\Filament\Appuser\Resources\StatuscategoryResource\Pages;

use App\Filament\Appuser\Resources\StatuscategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatuscategory extends EditRecord
{
    protected static string $resource = StatuscategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
