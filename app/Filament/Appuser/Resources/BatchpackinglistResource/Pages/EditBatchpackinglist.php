<?php

namespace App\Filament\Appuser\Resources\BatchpackinglistResource\Pages;

use App\Filament\Appuser\Resources\BatchpackinglistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchpackinglist extends EditRecord
{
    protected static string $resource = BatchpackinglistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
