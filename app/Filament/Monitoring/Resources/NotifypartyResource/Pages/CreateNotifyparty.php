<?php

namespace App\Filament\Monitoring\Resources\NotifypartyResource\Pages;

use App\Filament\Monitoring\Resources\NotifypartyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNotifyparty extends CreateRecord
{
    protected static string $resource = NotifypartyResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
