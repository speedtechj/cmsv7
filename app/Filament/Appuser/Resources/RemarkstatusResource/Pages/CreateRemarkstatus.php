<?php

namespace App\Filament\Appuser\Resources\RemarkstatusResource\Pages;

use App\Models\User;
use Filament\Actions;

use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Appuser\Resources\RemarkstatusResource;

class CreateRemarkstatus extends CreateRecord
{
    protected static string $resource = RemarkstatusResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        $data['assign_by'] = auth()->id();


        return $data;

    }
    protected function beforeCreate(): void
{
    if($this->data['booking_id'] == null && $this->data['manual_invoice'] == null){
        Notification::make()
            ->title('Validation Error')
            ->body('Please enter a valid  invoice.')
            ->icon('heroicon-o-x-circle')
            ->iconColor('danger')
            ->send();
         $this->form->fill([]);
           $this->halt();



    }

}
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // protected function afterCreate(): void
    // {
    //     $recipients = $this->record->assign_to;


    //    Notification::make()
    //    ->title('New Request Created')
    //    ->body($this->record->statuscategory->description. ' </br> '.
    //    'Created by'.' '. $this->record->user->first_name . ' ' . $this->record->user->last_name)
    //    ->icon('heroicon-o-information-circle')
    //    ->iconColor('success')
    //    ->actions([
    //         Action::make('Reply')
    //         ->url(RemarkstatusResource::getUrl(panel:'twelve24'))
    //    ])
    //    ->sendToDatabase(User::where('id', $recipients)->first());
    // }
//     protected function getFormActions(): array
// {

//     return [
//         $this->getCreateFormAction()
//             ->disabled(),
//     ];
// }
}
