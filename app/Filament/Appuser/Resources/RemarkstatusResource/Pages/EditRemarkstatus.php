<?php

namespace App\Filament\Appuser\Resources\RemarkstatusResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use App\Filament\Appuser\Resources\RemarkstatusResource;

class EditRemarkstatus extends EditRecord
{
    protected static string $resource = RemarkstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function beforeFill(): void
    {
        
        $this->form->getRecord()->sender = $this->record->booking->sender->full_name;
        $this->form->getRecord()->senderaddress = $this->record->booking->senderaddress->address;
        $this->form->getRecord()->receiver = $this->record->booking->receiver->full_name;
        $this->form->getRecord()->receiveraddress = $this->record->booking->receiveraddress->address;
    }
    protected function afterSave(): void
    {

        if ($this->record->status == 'Closed') {

            $this->record->update([
                'is_resolved' => true,
            ]);
            $recipients = $this->record->assign_by;
            Notification::make()
                ->title($this->record->statuscategory->description . ' ' . 'Request Closed & Resolved')
                ->body('Resolved by' .' '.auth()->user()->full_name)
                ->icon('heroicon-o-information-circle')
                ->iconColor('success')
                ->actions([
                    Action::make('View Reply')
                    ->url(RemarkstatusResource::getUrl(panel:'twelve24'))
                ])
                ->sendToDatabase(User::where('id', $recipients)->first());
        }
    }
}
