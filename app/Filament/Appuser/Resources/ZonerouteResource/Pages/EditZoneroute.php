<?php

namespace App\Filament\Appuser\Resources\ZonerouteResource\Pages;

use App\Filament\Appuser\Resources\ZonerouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZoneroute extends EditRecord
{
    protected static string $resource = ZonerouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
