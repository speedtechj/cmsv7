<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\Widgets;

use App\Models\Zone;
use App\Models\Batch;
use App\Models\Booking;
use App\Models\ZoneSummary;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Appuser\Resources\ShipmentstatusResource\Pages\ListShipmentstatuses;

class StatusOverview extends BaseWidget
{
    use InteractsWithPageTable;
    
    protected function getTablePage()
    {
        return ListShipmentstatuses::class;

    }
    
    protected function getStats(): array
    {
        $zones = Zone::all();
        $zonecnt = Zone::count();
        $currentbatch = Batch::where('is_current',1)->first()->id;
        $batchid = $this->getPageTableQuery()->get()->first()->batch_id ?? $currentbatch;
        $totalboxes = Booking::where('batch_id',$batchid)->where('is_deliver',false)->count();
        
        $stats = [
            Stat::make('Total Boxes Undelivered', $totalboxes)
                ->description('Overall total boxes')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
        foreach ($zones as $zone) {
            $stats[] = Stat::make('Total Boxes Undelivered',
            Booking::where('batch_id',$batchid)
            ->where('is_deliver',false)
            ->where('zone_id',$zone->id)
            ->count())
                ->description($zone->description)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary');
           
        }
       
           return $stats;
            
    }
}
