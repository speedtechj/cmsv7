<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Appuser\Resources\ShipmentstatusResource;
use App\Filament\Appuser\Resources\ShipmentstatusResource\Widgets\StatusOverview;

class ListShipmentstatuses extends ListRecords
{
    protected static string $resource = ShipmentstatusResource::class;
    use ExposesTableToWidgets;
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
//     protected function paginateTableQuery(Builder $query): Paginator
// {
//    // return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
// }
    protected function getHeaderWidgets(): array
    {
        return [
      //      StatusOverview::class,
            
        ];
    }
}
