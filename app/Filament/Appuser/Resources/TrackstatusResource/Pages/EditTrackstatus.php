<?php

namespace App\Filament\Appuser\Resources\TrackstatusResource\Pages;

use App\Filament\Appuser\Resources\TrackstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrackstatus extends EditRecord
{
    protected static string $resource = TrackstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
