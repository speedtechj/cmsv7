<?php

namespace App\Filament\Twelve24\Resources\StatushipmentResource\Pages;

use App\Filament\Twelve24\Resources\StatushipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatushipments extends ListRecords
{
    protected static string $resource = StatushipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
