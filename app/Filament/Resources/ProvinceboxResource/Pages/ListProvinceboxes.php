<?php

namespace App\Filament\Resources\ProvinceboxResource\Pages;

use App\Filament\Resources\ProvinceboxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProvinceboxes extends ListRecords
{
    protected static string $resource = ProvinceboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
       //     Actions\CreateAction::make(),
        ];
    }
}
