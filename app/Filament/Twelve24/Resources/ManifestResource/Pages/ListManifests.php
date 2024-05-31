<?php

namespace App\Filament\Twelve24\Resources\ManifestResource\Pages;

use App\Filament\Twelve24\Resources\ManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManifests extends ListRecords
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
