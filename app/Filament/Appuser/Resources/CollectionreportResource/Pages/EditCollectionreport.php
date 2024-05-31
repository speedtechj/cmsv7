<?php

namespace App\Filament\Appuser\Resources\CollectionreportResource\Pages;

use App\Filament\Appuser\Resources\CollectionreportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollectionreport extends EditRecord
{
    protected static string $resource = CollectionreportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
