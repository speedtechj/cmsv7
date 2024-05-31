<?php

namespace App\Filament\Monitoring\Resources\NotifypartyResource\Pages;

use App\Filament\Monitoring\Resources\NotifypartyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifyparties extends ListRecords
{
    protected static string $resource = NotifypartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
