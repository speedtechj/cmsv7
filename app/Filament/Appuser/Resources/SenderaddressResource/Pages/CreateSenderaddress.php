<?php

namespace App\Filament\Appuser\Resources\SenderaddressResource\Pages;

use Filament\Actions;
use Illuminate\Http\Request;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\SenderaddressResource;

class CreateSenderaddress extends CreateRecord
{
    protected static string $resource = SenderaddressResource::class;
    // RelationManager $livewire
    // protected function beforeCreate(RelationManager $livewire)

    // {
    //    dd($livewire->ownerRecord);
    // }
    protected function mutateFormDataBeforeCreate(array $data): array
    {

      
        $data['user_id'] = auth()->id();

        return $data;

    }
    
}
