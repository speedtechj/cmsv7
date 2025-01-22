<?php

namespace App\Filament\Appuser\Resources\BatchpackinglistResource\Pages;

use App\Filament\Appuser\Resources\BatchpackinglistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBatchpackinglists extends ListRecords
{
    protected static string $resource = BatchpackinglistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
