<?php

namespace App\Filament\Allport\Resources\ShippingbookingResource\Pages;

use App\Filament\Allport\Resources\ShippingbookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingbookings extends ListRecords
{
    protected static string $resource = ShippingbookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
