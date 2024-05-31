<?php

namespace App\Filament\Exports;

use App\Models\Alltransaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AlltransactionExporter extends Exporter
{
    protected static ?string $model = Alltransaction::class;

    public static function getColumns(): array
    {
        return [
         
            
            ExportColumn::make('sender.full_name')->label('Sender Name'),
            ExportColumn::make('senderaddress.address')->label('Sender Address'),
            ExportColumn::make('booking_invoice')->label('Invoice Number'),
            ExportColumn::make('manual_invoice')->label('Manual Invoice'),
            ExportColumn::make('sender.mobile_no')->label('Mobile Number'),
            ExportColumn::make('sender.home_no')->label('Home Number'),
            ExportColumn::make('boxtype.description')->label('Box Type'),
            ExportColumn::make('booking_date')->label('Transaction Date'),
            
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your alltransaction export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
