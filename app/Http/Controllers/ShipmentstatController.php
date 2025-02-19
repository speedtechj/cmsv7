<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\ShipmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class ShipmentstatController extends Controller
{
        public function statmail(Booking $record): void
    {
        
            $recipients = ['arbiasjr@gmail.com', 'rosemariegaje2019@gmail.com'];
            
            foreach ($recipients as $recipient) {
                Mail::to($recipient)->send(new ShipmentMail($record));
            }
        
        
       
    }

   
}
