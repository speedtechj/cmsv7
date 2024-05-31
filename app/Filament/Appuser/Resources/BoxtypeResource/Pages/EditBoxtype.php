<?php

namespace App\Filament\Appuser\Resources\BoxtypeResource\Pages;

use App\Filament\Appuser\Resources\BoxtypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBoxtype extends EditRecord
{
    protected static string $resource = BoxtypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
