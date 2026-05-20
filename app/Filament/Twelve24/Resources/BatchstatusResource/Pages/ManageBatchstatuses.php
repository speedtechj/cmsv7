<?php

namespace App\Filament\Twelve24\Resources\BatchstatusResource\Pages;

use App\Filament\Twelve24\Resources\BatchstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBatchstatuses extends ManageRecords
{
    protected static string $resource = BatchstatusResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}
