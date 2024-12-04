<?php

namespace App\Filament\Appuser\Resources\CustomerloginfoResource\Pages;

use App\Filament\Appuser\Resources\CustomerloginfoResource;
use App\Filament\Appuser\Resources\CustomerloginfoResource\Widgets\CallWidget;
use App\Filament\Appuser\Resources\CustomerloginfoResource\Widgets\TotalCallChartWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
 
class ListCustomerloginfos extends ListRecords
{
    protected static string $resource = CustomerloginfoResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
           
            CallWidget::class,
            TotalCallChartWidget::class,
        ];
    }
   
}
