<?php

namespace App\Filament\Appuser\Resources\BarangayphilResource\Pages;

use App\Filament\Appuser\Resources\BarangayphilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangayphils extends ListRecords
{
    protected static string $resource = BarangayphilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
