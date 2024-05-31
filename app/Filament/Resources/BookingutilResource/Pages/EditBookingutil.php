<?php

namespace App\Filament\Resources\BookingutilResource\Pages;

use App\Filament\Resources\BookingutilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookingutil extends EditRecord
{
    protected static string $resource = BookingutilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
