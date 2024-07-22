<?php

namespace App\Filament\Appuser\Resources\PosinvoiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Appuser\Resources\PosResource;
use App\Filament\Appuser\Resources\PosinvoiceResource;

class CreatePosinvoice extends CreateRecord
{
    protected static string $resource = PosinvoiceResource::class;
    protected function getRedirectUrl(): string
    {
       
        return PosResource::getUrl('view', ['record' => $this->data['sender_id']]);
    }
}
