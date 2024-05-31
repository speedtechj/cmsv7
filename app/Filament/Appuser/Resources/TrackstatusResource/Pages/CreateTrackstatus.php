<?php

namespace App\Filament\Appuser\Resources\TrackstatusResource\Pages;

use App\Filament\Appuser\Resources\TrackstatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrackstatus extends CreateRecord
{
    protected static string $resource = TrackstatusResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        
        
        return $data;

    }
}
