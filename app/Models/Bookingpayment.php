<?php

namespace App\Models;


use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookingpayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'payment_amount' => MoneyCast::class,
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function paymenttype()
    {
        return $this->belongsTo(Paymenttype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    
}
