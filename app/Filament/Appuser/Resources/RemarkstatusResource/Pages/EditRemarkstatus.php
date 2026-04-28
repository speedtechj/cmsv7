<?php

namespace App\Filament\Appuser\Resources\RemarkstatusResource\Pages;

use App\Filament\Appuser\Resources\RemarkstatusResource;
use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRemarkstatus extends EditRecord
{
    protected static string $resource = RemarkstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
//     protected function mutateFormDataBeforeFill(array $data): array
// {


//    $data['manual_invoice'] = $data['manual_invoice'];

//    $data['booking_id'] = $data['booking_id'];

//     dd($data);
//     return $data;


// }

// protected function beforeSave(): void
//     {
//       dd($this->data);

//     }
    protected function beforeFill(): void
    {
      //  dd($this->record);
      $this->form->getRecord()->manual_invoice = $this->record->booking->manual_invoice;
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
                        ->url(RemarkstatusResource::getUrl(
                            'edit',
                            [
                                'record' => $this->record->id,
                            ],
                            panel: 'twelve24'
                        ))

                ])
                ->sendToDatabase(User::where('id', $recipients)->first());
        }
    }
}
