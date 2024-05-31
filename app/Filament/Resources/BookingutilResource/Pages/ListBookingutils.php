<?php

namespace App\Filament\Resources\BookingutilResource\Pages;

use App\Filament\Resources\BookingutilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingutils extends ListRecords
{
    protected static string $resource = BookingutilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
