<?php

namespace App\Filament\Appuser\Resources\SenderResource\Pages;

use App\Filament\Appuser\Resources\SenderResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListSenders extends ListRecords
{
    protected static string $resource = SenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
   public function mount(): void
    {
        parent::mount();

        Notification::make()
            ->title('Instructions for Searching')
            ->body('After typing, press Tab Key to begin the search not Enter Key.')
            ->persistent()
            ->success()
            ->send();
    }
    protected function paginateTableQuery(Builder $query): Paginator
{
    return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
}
}
