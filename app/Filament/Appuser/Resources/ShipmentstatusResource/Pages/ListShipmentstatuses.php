<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Appuser\Resources\ShipmentstatusResource;

class ListShipmentstatuses extends ListRecords
{
    protected static string $resource = ShipmentstatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
}
}
