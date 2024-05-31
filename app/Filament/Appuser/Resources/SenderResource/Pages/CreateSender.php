<?php

namespace App\Filament\Appuser\Resources\SenderResource\Pages;

use App\Filament\Appuser\Resources\SenderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSender extends CreateRecord
{
    protected static string $resource = SenderResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        $data['branch_id'] = auth()->user()->branch_id;
        
        return $data;

    }
}
