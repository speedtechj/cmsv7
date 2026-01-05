<?php

namespace App\Filament\Resources\ProvinceboxResource\Pages;

use App\Filament\Resources\ProvinceboxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProvincebox extends EditRecord
{
    protected static string $resource = ProvinceboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
    //        Actions\DeleteAction::make(),
        ];
    }
}
