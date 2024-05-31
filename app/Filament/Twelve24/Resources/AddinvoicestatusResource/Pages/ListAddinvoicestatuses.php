<?php

namespace App\Filament\Twelve24\Resources\AddinvoicestatusResource\Pages;

use App\Filament\Twelve24\Resources\AddinvoicestatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListAddinvoicestatuses extends ListRecords
{
    protected static string $resource = AddinvoicestatusResource::class;

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
