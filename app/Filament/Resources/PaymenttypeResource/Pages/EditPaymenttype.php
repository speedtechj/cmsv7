<?php

namespace App\Filament\Resources\PaymenttypeResource\Pages;

use App\Filament\Resources\PaymenttypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymenttype extends EditRecord
{
    protected static string $resource = PaymenttypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
