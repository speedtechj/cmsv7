<?php

namespace App\Filament\Twelve24\Resources\StatushipmentResource\Pages;

use App\Filament\Twelve24\Resources\StatushipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatushipment extends EditRecord
{
    protected static string $resource = StatushipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
       //     Actions\DeleteAction::make(),
        ];
    }
}
