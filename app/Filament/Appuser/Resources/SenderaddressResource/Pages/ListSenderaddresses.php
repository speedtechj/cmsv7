<?php

namespace App\Filament\Appuser\Resources\SenderaddressResource\Pages;

use App\Filament\Appuser\Resources\SenderaddressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSenderaddresses extends ListRecords
{
    protected static string $resource = SenderaddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
