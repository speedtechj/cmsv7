<?php

namespace App\Filament\Appuser\Resources\BatchpackinglistResource\Pages;

use App\Filament\Appuser\Resources\BatchpackinglistResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBatchpackinglist extends CreateRecord
{
    protected static string $resource = BatchpackinglistResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       
        $data['user_id'] = auth()->id();
       
        return $data;

    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
