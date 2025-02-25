<?php

namespace App\Filament\Resources\ZoneSummaryResource\Pages;

use App\Filament\Resources\ZoneSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZoneSummary extends EditRecord
{
    protected static string $resource = ZoneSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
