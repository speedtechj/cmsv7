<?php

namespace App\Filament\Appuser\Resources\TrackstatusResource\Pages;

use App\Filament\Appuser\Resources\TrackstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrackstatuses extends ListRecords
{
    protected static string $resource = TrackstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
