<?php

namespace App\Filament\Appuser\Resources\CustomerloginfoResource\Widgets;

use App\Models\User;
use App\Models\Calllog;
use App\Models\Logcallsetting;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CallWidget extends BaseWidget
{
    protected function getStats(): array
    {
       $callid = Logcallsetting::where('is_active',1)->first()->calltype_id ?? 0;
       if($callid == 0){
        Notification::make()
        ->title('Please set an active call type in Log settings')
        ->warning()
        ->send();
        }
        return [
            Stat::make(Auth::User()->full_name, Calllog::where('calltype_id',$callid)->count())
            ->description('Total Calls')
            ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
            ->color('success'),
        ];
    }
}
