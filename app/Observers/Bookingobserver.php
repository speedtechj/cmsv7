<?php

namespace App\Observers;

use App\Models\Batch;
use App\Models\Booking;
use App\Models\Boxtype;
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
      
        $skiddingresult = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
            ->orWhere('virtual_invoice', $booking->manual_invoice)->first();
       

       
        if ($skiddingresult) {

            $skiddingresult->update(
                [
                   
                    'boxtype_id' => $booking->boxtype_id,
                    'is_encode' => true,
                    'booking_id' => $booking->id,
                    'cbm' => $boxcbm,
                ]
            );
            
            $booking->update([ 'batch_id' =>  $skiddingresult->batch_id]);

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
            $boxtype = Boxtype::find($booking->boxtype_id);
          
            $length = $boxtype->lenght ?? 0;
            $width = $boxtype->width ?? 0;
            $height = $boxtype->height ?? 0;
            $boxcbm = round($length * $width * $height / 61024, 2);
        }
       
        $updatebooking = Booking::find($booking->id);
      $skiddingresult = Skiddinginfo::where('virtual_invoice', $booking->booking_invoice)
            ->orWhere('virtual_invoice', $booking->manual_invoice)->first();

            if ($skiddingresult) {
              
                $skiddingresult->update(
                    [
                        'boxtype_id' =>  $updatebooking->boxtype_id,
                        'is_encode' => true,
                        'booking_id' =>  $updatebooking->id,
                        'cbm' => $boxcbm,
                    ]
                );
                // $booking->update(['batch_id' =>  $skiddingresult->batch_id]);
                $updatebooking->update(['batch_id' =>  $skiddingresult->batch_id]);
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
