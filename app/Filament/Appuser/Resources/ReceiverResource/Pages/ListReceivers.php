<?php

namespace App\Filament\Appuser\Resources\ReceiverResource\Pages;

use App\Filament\Appuser\Resources\ReceiverResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceivers extends ListRecords
{
    protected static string $resource = ReceiverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
