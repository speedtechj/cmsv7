<?php

namespace App\Filament\Appuser\Resources\LogcallsettingResource\Pages;

use App\Filament\Appuser\Resources\LogcallsettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogcallsettings extends ListRecords
{
    protected static string $resource = LogcallsettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
