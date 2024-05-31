<?php

namespace App\Filament\Appuser\Resources\UnpickedboxesResource\Pages;

use App\Filament\Appuser\Resources\UnpickedboxesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnpickedboxes extends ListRecords
{
    protected static string $resource = UnpickedboxesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
           UnpickedboxesResource\Widgets\Unpickedbox::class,
        ];
    }
}
