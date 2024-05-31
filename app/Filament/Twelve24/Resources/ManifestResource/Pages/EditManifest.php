<?php

namespace App\Filament\Twelve24\Resources\ManifestResource\Pages;

use App\Filament\Twelve24\Resources\ManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManifest extends EditRecord
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
