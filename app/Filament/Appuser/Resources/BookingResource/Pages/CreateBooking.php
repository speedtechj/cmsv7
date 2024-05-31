<?php

namespace App\Filament\Appuser\Resources\BookingResource\Pages;

use App\Filament\Appuser\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
    
}
