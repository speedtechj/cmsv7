<?php

namespace App\Filament\Exports;

use Carbon\Carbon;
use App\Models\Batch;
use App\Models\Trackstatus;
use App\Models\Shipmentstatus;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ShipmentstatusExporter extends Exporter
{
    protected static ?string $model = Shipmentstatus::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('booking_invoice')
            ->label('Generated Invoice'),
            ExportColumn::make('manual_invoice')
            ->label('Manual Invoice'),
            ExportColumn::make('sender.full_name')
            ->label('Sender Name'),
            ExportColumn::make('receiver.full_name')
            ->label('Receiver Name'),
            ExportColumn::make('receiveraddress.address'),
            ExportColumn::make('receiveraddress.provincephil.name')
            ->label('Province'),
            ExportColumn::make('receiveraddress.cityphil.name')
            ->label('City'),
            ExportColumn::make('boxtype.description')
            ->label('BoxType'),
            ExportColumn::make('batch.id')
            ->label('BatchNumber')
            ->formatStateUsing(function ($state){
               $batchno = Batch::where('id',$state)->first();
               return $batchno->batchno . '-'. $batchno->batch_year;
            }),
           
            ExportColumn::make('no_of_days')
            ->label('No of Days')
            ->state(function(Model $record){
                return $record->receiveraddress->cityphil->no_days;
            }),
           ExportColumn::make('Due_Days')
           ->label('Due Days')
            ->state(function (Model $record){
                $endpoint = Trackstatus::where('code', 'ed')->first()->id;
                $end_point_stat = $record->invoicestatuses->where('trackstatus_id', $endpoint)->first();
                if ($end_point_stat == null) {
                    $origin = Trackstatus::where('code', 'op')->first()->id;
                    $origin_date = Carbon::parse($record->invoicestatuses->where('trackstatus_id', $origin)->first()->date_update ?? now());
                    $now = Carbon::now()->addDays(1);
                    $diff_date = $origin_date->diffInDays($now, false);
                    $agingdays = $record->receiveraddress->cityphil->no_days;
                //    dd($agingdays, $diff_date);
                    if ($diff_date > $agingdays) {
                        $days_due = $diff_date - $agingdays;
                   return $days_due;
                    } else {
                        return 0;
                    }
                }else {
                    return 0;
                }
            
            }),
            
            // ExportColumn::make('is_deliver'),
            
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your shipmentstatus export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
