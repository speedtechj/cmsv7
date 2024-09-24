<?php

namespace App\Filament\Appuser\Resources\CalltypeResource\Pages;

use App\Filament\Appuser\Resources\CalltypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCalltype extends CreateRecord
{
    protected static string $resource = CalltypeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;

    }
}
