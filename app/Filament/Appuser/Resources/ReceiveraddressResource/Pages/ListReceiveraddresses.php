<?php

namespace App\Filament\Appuser\Resources\ReceiveraddressResource\Pages;

use App\Filament\Appuser\Resources\ReceiveraddressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiveraddresses extends ListRecords
{
    protected static string $resource = ReceiveraddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
