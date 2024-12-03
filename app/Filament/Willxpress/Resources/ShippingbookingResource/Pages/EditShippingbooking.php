<?php

namespace App\Filament\Willxpress\Resources\ShippingbookingResource\Pages;

use App\Filament\Willxpress\Resources\ShippingbookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingbooking extends EditRecord
{
    protected static string $resource = ShippingbookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
