<?php

namespace App\Filament\Twelve24\Resources\StatuscategoryResource\Pages;

use App\Filament\Twelve24\Resources\StatuscategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatuscategory extends EditRecord
{
    protected static string $resource = StatuscategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
