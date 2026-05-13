<?php

namespace App\Filament\Appuser\Resources\BatchstatusResource\Pages;

use App\Filament\Appuser\Resources\BatchstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBatchstatuses extends ManageRecords
{
    protected static string $resource = BatchstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
         //   Actions\CreateAction::make(),
        ];
    }
}
