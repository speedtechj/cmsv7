<?php

namespace App\Filament\Appuser\Resources\ProvincephilResource\Pages;

use App\Filament\Appuser\Resources\ProvincephilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvincephil extends EditRecord
{
    protected static string $resource = ProvincephilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
