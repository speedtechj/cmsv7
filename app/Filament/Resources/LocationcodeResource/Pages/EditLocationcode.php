<?php

namespace App\Filament\Resources\LocationcodeResource\Pages;

use App\Filament\Resources\LocationcodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationcode extends EditRecord
{
    protected static string $resource = LocationcodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
