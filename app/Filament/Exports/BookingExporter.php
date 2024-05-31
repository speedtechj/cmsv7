<?php

namespace App\Filament\Exports;

use App\Models\Booking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BookingExporter extends Exporter
{
    protected static ?string $model = Booking::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('booking_invoice')->label('Invoice'),
            ExportColumn::make('manual_invoice')->label('Manual Invoice'),
            ExportColumn::make('sender.full_name')->label('Sender'),
            ExportColumn::make('senderaddress.address')->label('Sender Address'),
            ExportColumn::make('senderaddress.quadrant')->label('Quadrant'),
            ExportColumn::make('senderaddress.provincecan.name')->label('Province'),
            ExportColumn::make('senderaddress.citycan.name')->label('City/Municipality'),
            ExportColumn::make('senderaddress.postal_code')->label('Postal Code'),
            ExportColumn::make('sender.mobile_no')->label('Mobile No'),
            ExportColumn::make('sender.home_no')->label('Home No'),
            ExportColumn::make('zone.description')->label('Zone'),
            ExportColumn::make('boxtype.description')->label('Box Type'),
            ExportColumn::make('booking_date')->label('Booking Date'),
            ExportColumn::make('start_time'),
            ExportColumn::make('end_time'),
            ExportColumn::make('total_inches'),
            ExportColumn::make('dimension'),
            ExportColumn::make('note'),
            ExportColumn::make('catextracharge.name')->label('Extra Charge'),
            ExportColumn::make('extracharge_amount')->label('Extra Charge Amount'),
            ExportColumn::make('discount.discount_amount'),
            ExportColumn::make('total_price'),
            ExportColumn::make('payment_balance'),
           
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your booking export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
