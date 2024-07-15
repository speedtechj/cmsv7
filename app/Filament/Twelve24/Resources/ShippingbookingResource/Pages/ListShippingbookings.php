<?php

namespace App\Filament\Twelve24\Resources\ShippingbookingResource\Pages;

use App\Filament\Twelve24\Resources\ShippingbookingResource;
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
