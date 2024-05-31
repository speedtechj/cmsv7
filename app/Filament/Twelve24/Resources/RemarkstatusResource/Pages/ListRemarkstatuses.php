<?php

namespace App\Filament\Twelve24\Resources\RemarkstatusResource\Pages;

use App\Filament\Twelve24\Resources\RemarkstatusResource;
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
