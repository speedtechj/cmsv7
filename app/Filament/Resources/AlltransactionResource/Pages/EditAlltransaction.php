<?php

namespace App\Filament\Resources\AlltransactionResource\Pages;

use App\Filament\Resources\AlltransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlltransaction extends EditRecord
{
    protected static string $resource = AlltransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
