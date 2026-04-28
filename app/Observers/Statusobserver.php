<?php

namespace App\Observers;

use App\Filament\Twelve24\Resources\RemarkstatusResource;
use App\Models\Remarkstatus;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;



class Statusobserver
{
    /**
     * Handle the Remarkstatus "created" event.
     */
    public function created(Remarkstatus $remarkstatus): void
    {
        $panel = Filament::getCurrentPanel();

        $panelId = $panel->getId();        // panel ID (e.g. 'admin')

        if ($panelId === 'appuser') {

            $recipients = $remarkstatus->assign_to;


            Notification::make()
                ->title('New Request Created')
                ->body($remarkstatus->statuscategory->description . ' </br> ' .
                    'Created by' . ' ' . $remarkstatus->user->first_name . ' ' . $remarkstatus->user->last_name)
                ->icon('heroicon-o-information-circle')
                ->iconColor('success')
                ->actions([
                    Action::make('Reply')
                        ->url(RemarkstatusResource::getUrl(
                            'edit',
                            [
                                'record' => $remarkstatus->id,
                            ],
                            panel: 'twelve24'
                        ))

                ])
                ->sendToDatabase(User::whereIn('id', $recipients)->get());
        } else {
            $recipients = $remarkstatus->assign_to;
            Notification::make()
                ->title('New Request Created')
                ->body($remarkstatus->statuscategory->description . ' </br> ' .
                    'Created by' . ' ' . $remarkstatus->user->first_name . ' ' . $remarkstatus->user->last_name)
                ->icon('heroicon-o-information-circle')
                ->iconColor('success')
                ->actions([
                    Action::make('Reply')

                        ->url(RemarkstatusResource::getUrl(
                            'edit',
                            [
                                'record' => $remarkstatus->id,
                            ],
                            panel: 'appuser'
                        ))

                ])
                ->sendToDatabase(User::whereIn('id', $recipients)->get());
        }


    }

    /**
     * Handle the Remarkstatus "updated" event.
     */



    /**
     * Handle the Remarkstatus "deleted" event.
     */
    public function deleted(Remarkstatus $remarkstatus): void {}

    /**
     * Handle the Remarkstatus "restored" event.
     */
    public function restored(Remarkstatus $remarkstatus): void
    {
        //
    }

    /**
     * Handle the Remarkstatus "force deleted" event.
     */
    public function forceDeleted(Remarkstatus $remarkstatus): void
    {
        //
    }
}
