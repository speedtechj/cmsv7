<?php

namespace App\Filament\Twelve24\Resources\SearchinvoiceResource\Pages;

use App\Filament\Twelve24\Resources\SearchinvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
class ListSearchinvoices extends ListRecords
{
    protected static string $resource = SearchinvoiceResource::class;

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
