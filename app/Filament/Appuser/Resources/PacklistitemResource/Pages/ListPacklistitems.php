<?php

namespace App\Filament\Appuser\Resources\PacklistitemResource\Pages;

use App\Filament\Appuser\Resources\PacklistitemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacklistitems extends ListRecords
{
    protected static string $resource = PacklistitemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
