<?php

namespace App\Filament\Monitoring\Resources\ShippingbookingResource\Pages;

use App\Filament\Monitoring\Resources\ShippingbookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingbookings extends ListRecords
{
    protected static string $resource = ShippingbookingResource::class;
    // public $defaultAction = 'testAction';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    // public function testAction()
    // {
    // //    dd('test');
    // }
}
