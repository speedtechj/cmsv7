<?php

namespace App\Filament\Appuser\Resources\TransactionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Appuser\Resources\SenderResource;
use App\Filament\Appuser\Resources\TransactionResource;
use Filament\Resources\RelationManagers\RelationManager;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
       
        return SenderResource::getUrl('edit', ['record' => $this->data['sender_id']]);
    }
    protected function beforeFill(): void
    {
        Notification::make()
        ->title('When booking an online drop-off, please make sure to uncheck the Box Received option.')
        ->danger()
        ->persistent()
        ->send();
    }
    
}
