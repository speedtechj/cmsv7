<?php

namespace App\Filament\Appuser\Resources\SearchinvoiceResource\Pages;

use App\Filament\Appuser\Resources\SearchinvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ListSearchinvoices extends ListRecords
{
    protected static string $resource = SearchinvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    public function mount(): void
    {
        parent::mount();

        Notification::make()
            ->title('Instructions for Searching')
            ->body(Str::markdown('<div style="color:#F5BA07;font-weight:bold;font-size:15px;">After typing, press Tab Key to begin the search not Enter Key.</div>'))
            ->persistent()
            ->info()
            ->send();
    }
    protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
}
}
