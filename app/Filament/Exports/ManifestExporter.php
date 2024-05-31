<?php

namespace App\Filament\Exports;

use App\Models\Manifest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ManifestExporter extends Exporter
{
    protected static ?string $model = Manifest::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('booking_invoice')
            ->label('Invoice'),
            ExportColumn::make('manual_invoice')
            ->label('Manual Invoice'),
            ExportColumn::make('batch.batchno')
            ->label('Batch No'),
            ExportColumn::make('boxtype.description')
            ->label('Box Type'),
            ExportColumn::make('quantity')->default('1')
            ->label('Quantity'),
            ExportColumn::make('sender.full_name')
            ->label('Sender'),
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
