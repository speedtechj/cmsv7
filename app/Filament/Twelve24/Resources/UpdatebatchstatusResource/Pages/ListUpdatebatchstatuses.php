<?php

namespace App\Filament\Twelve24\Resources\UpdatebatchstatusResource\Pages;

use App\Filament\Twelve24\Resources\UpdatebatchstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpdatebatchstatuses extends ListRecords
{
    protected static string $resource = UpdatebatchstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
