<?php

namespace App\Filament\Appuser\Resources\RemarkstatusResource\Pages;

use App\Filament\Appuser\Resources\RemarkstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRemarkstatuses extends ListRecords
{
    protected static string $resource = RemarkstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
