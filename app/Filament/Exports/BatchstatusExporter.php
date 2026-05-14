<?php

namespace App\Filament\Exports;

use App\Models\Batchstatus;
use App\Models\Invoicestatus;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BatchstatusExporter extends Exporter
{
    protected static ?string $model = Batchstatus::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice')
                ->label('Invoice')
                ->state(function ($record) {
                    if ($record->manual_invoice != null) {
                        return $record->manual_invoice;
                    } else {
                        return $record->generated_invoice;
                    }
                }),
            ExportColumn::make('batch.batchno')->label('Batch No'),
            ExportColumn::make('latest_status')->label('Latest Status')
            ->state(function ($record) {
                   $status = Invoicestatus::where('booking_id', $record->booking_id)
                            ->with('trackstatus')
                            ->latest() // latest by created_at
                            ->first();
                        return $status->trackstatus->description;
                }),
            ExportColumn::make('date_update')->label('Status Date'),
            ExportColumn::make('boxtype.description')->label('Box Type'),
            ExportColumn::make('sender.full_name')->label('Sender Name'),
            ExportColumn::make('receiver.full_name')->label('Receiver Name'),
            ExportColumn::make('booking.receiveraddress.address')->label('Receiver Address'),
            ExportColumn::make('barangayphil.name')->label('Barangay'),
            ExportColumn::make('cityphil.name')->label('City'),
            ExportColumn::make('provincephil.name')->label('Province'),




        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your batchstatus export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
