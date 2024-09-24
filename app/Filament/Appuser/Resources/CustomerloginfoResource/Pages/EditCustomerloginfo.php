<?php

namespace App\Filament\Appuser\Resources\CustomerloginfoResource\Pages;

use App\Filament\Appuser\Resources\CustomerloginfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerloginfo extends EditRecord
{
    protected static string $resource = CustomerloginfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
