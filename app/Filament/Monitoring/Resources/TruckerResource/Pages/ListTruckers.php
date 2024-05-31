<?php

namespace App\Filament\Monitoring\Resources\TruckerResource\Pages;

use App\Filament\Monitoring\Resources\TruckerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTruckers extends ListRecords
{
    protected static string $resource = TruckerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
