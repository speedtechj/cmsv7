<?php

namespace App\Filament\Monitoring\Resources\ShippingagentResource\Pages;

use App\Filament\Monitoring\Resources\ShippingagentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingagents extends ListRecords
{
    protected static string $resource = ShippingagentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
