<?php

namespace App\Filament\Exports;

use App\Models\Booking;
use App\Models\Manifest;
use App\Models\Locationcode;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ManifestExporter extends Exporter
{
    protected static ?string $model = Manifest::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice')
            ->label('Invoice')
            ->state(function ($record) {
                if($record->manual_invoice != null){
                    return $record->manual_invoice;
                }else{
                    return $record->booking_invoice;
                }
            }),
            // ExportColumn::make('manual_invoice')
            // ->label('Manual Invoice'),
            ExportColumn::make('batch.batchno')
            ->label('Batch No'),
            ExportColumn::make('boxtype.description')
            ->label('Box Type'),
            ExportColumn::make('quantity')->default('1')
            ->label('Quantity'),
            ExportColumn::make('sender.full_name')
            ->label('Sender'),
            ExportColumn::make('sender.first_name')
            ->label('First Name')
            ->enabledByDefault(false),
            ExportColumn::make('sender.last_name')
            ->label('Last Name')
            ->enabledByDefault(false),
            ExportColumn::make('sender.email')
            ->label('Email')
            ->enabledByDefault(false),
            ExportColumn::make('receiver.full_name')
            ->label('Receiver'),
            ExportColumn::make('receiveraddress.address')
            ->label('Address'),
            ExportColumn::make('receiveraddress.barangayphil.name')
            ->label('Barangay'),
            ExportColumn::make('receiveraddress.provincephil.name')
            ->label('Province'),
            ExportColumn::make('receiveraddress.cityphil.name')->label('City'),
            ExportColumn::make('receiver.mobile_no')->label('Mobile Number'),
            ExportColumn::make('receiver.home_no')->label('Home Number'),
    ExportColumn::make('code')
            ->label('Location code')
             ->state(function ( Locationcode $state) {
                $state = Locationcode::all()->first();
        return $state->code;
    }),
        ExportColumn::make('receiveraddress.barangayphil.zoneroute_id')
           ->label('Route id'),
            
            
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your manifest export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
