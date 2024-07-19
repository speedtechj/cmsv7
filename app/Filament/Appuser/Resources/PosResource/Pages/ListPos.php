<?php

namespace App\Filament\Appuser\Resources\PosResource\Pages;

use App\Filament\Appuser\Resources\PosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
class ListPos extends ListRecords
{
    protected static string $resource = PosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function paginateTableQuery(Builder $query): CursorPaginator
{
    return $query->cursorPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
}
}
