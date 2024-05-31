<?php

namespace App\Filament\Appuser\Resources\SenderResource\Pages;

use App\Filament\Appuser\Resources\SenderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSender extends EditRecord
{
    protected static string $resource = SenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
