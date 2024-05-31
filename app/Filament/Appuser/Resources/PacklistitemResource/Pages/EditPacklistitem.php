<?php

namespace App\Filament\Appuser\Resources\PacklistitemResource\Pages;

use App\Filament\Appuser\Resources\PacklistitemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPacklistitem extends EditRecord
{
    protected static string $resource = PacklistitemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
