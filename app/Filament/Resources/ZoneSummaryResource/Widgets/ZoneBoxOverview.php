<?php

namespace App\Filament\Resources\ZoneSummaryResource\Widgets;

use App\Models\Zone;
use App\Models\Batch;
use Nette\Utils\Strings;
use App\Models\ZoneSummary;
use App\Models\Skiddinginfo;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\ZoneSummaryResource\Pages\ListZoneSummaries;

class ZoneBoxOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected ?string $heading = 'Analytics';
 
protected ?string $description = 'Summary of boxes in each zone';

protected int | string | array $columnSpan = 'full';

public function getColumns(): int
{
    return 4;
}

    protected function getTablePage()
    {
        return ListZoneSummaries::class;

    }
    protected function getStats(): array
    {
        
       
        $zones = Zone::all();
        $zonecnt = Zone::count();
        $currentbatch = Batch::where('is_current',1)->first()->id;
        $batchid = $this->getPageTableQuery()->get()->first()->batch_id ?? $currentbatch;
        $totalboxes = ZoneSummary::where('batch_id',$batchid)->count();
        
        $stats = [
            Stat::make('Total Boxes', $totalboxes)
                ->description('Overall total boxes')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
        foreach ($zones as $zone) {
            $stats[] = Stat::make('Total Boxes ', 
            ZoneSummary::where('batch_id',$batchid)->where('zone_id',$zone->id)->count()
            .' -> '. number_format(ZoneSummary::where('batch_id',$batchid)->where('zone_id',$zone->id)->count() / $totalboxes * 100,0) .'%'
            )
                ->description($zone->description)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary');
           
        }
       
           return $stats;
            
            
    
    }
    
    
}
