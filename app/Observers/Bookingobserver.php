<?php

namespace App\Observers;

use App\Models\Batch;
use App\Models\Booking;
use App\Models\Skiddinginfo;
use Filament\Notifications\Notification;

class Bookingobserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        if ($booking->boxtype_id == '4') {
            $length = $booking->irregular_length;
            $width = $booking->irregular_width;
            $height = $booking->irregular_height;
            $boxcbm = round($length * $width * $height / 61024, 2);

        } else {
            $length = $booking->boxtype->lenght ?? 0;
            $width = $booking->boxtype->width ?? 0;
            $height = $booking->boxtype->height ?? 0;
            $boxcbm = round($length * $width * $height / 61024, 2);
        }
        $currentbatch = Batch::where('is_current', true)->first();
        $skiddingresult = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
            ->orWhere('virtual_invoice', $booking->manual_invoice);
        $skiddingbatch = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
        ->orWhere('virtual_invoice', $booking->manual_invoice)->first();

       
        if ($skiddingresult->exists()) {
            $skiddingresult->update(
                [
                    'boxtype_id' => $booking->boxtype_id,
                    'is_encode' => true,
                    'booking_id' => $booking->id,
                    'cbm' => $boxcbm,
                ]
            );
                    if($skiddingbatch->batch_id == $currentbatch->id){
                        
                        $booking->update(['batch_id' => $currentbatch->id]);
                    }else {
                        $booking->update(['batch_id' => $skiddingbatch->batch_id]);
                    }

        }


    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {

        if ($booking->boxtype_id == '4') {
            $length = $booking->irregular_length;
            $width = $booking->irregular_width;
            $height = $booking->irregular_height;
            $boxcbm = round($length * $width * $height / 61024, 2);

        } else {
            $length = $booking->boxtype->lenght ?? 0;
            $width = $booking->boxtype->width ?? 0;
            $height = $booking->boxtype->height ?? 0;
            $boxcbm = round($length * $width * $height / 61024, 2);
        }
        $skiddingbatch = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
        ->orWhere('virtual_invoice', $booking->manual_invoice)->first();
        $skiddingresult = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
            ->orWhere('virtual_invoice', $booking->manual_invoice);

        if ($booking->batch_id == 23) {
            if ($skiddingresult->exists()) {
                $currentbatch = Batch::where('is_current', true)->first();
                $skiddingresult->update(
                    [
                        'boxtype_id' => $booking->boxtype_id,
                        'is_encode' => true,
                        'booking_id' => $booking->id,
                        'cbm' => $boxcbm,
                    ]
                );


                $booking->update(['batch_id' => $currentbatch->id]);





            }

        } else {
            if ($skiddingresult->exists()) {
               
                $skiddingresult->update(
                    [
                        'boxtype_id' => $booking->boxtype_id,
                        'is_encode' => true,
                        'booking_id' => $booking->id,
                        'cbm' => $boxcbm,
                    ]
                );







            }
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
