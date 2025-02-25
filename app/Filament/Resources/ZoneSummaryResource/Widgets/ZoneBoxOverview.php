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
        
        
        foreach ($zones as $zone) {
            $stats[] = Stat::make('Total Boxes ', ZoneSummary::where('batch_id',$batchid)->where('zone_id',$zone->id)->count())
                ->description($zone->description)
                ->color('primary');
            // $stats[] = Stat::make($zone->description, 'test')
            //     ->description('Total boxes in zone')
            //     ->color('primary');
        }
       
           return  $stats;
            
    
    }
    
    
}
