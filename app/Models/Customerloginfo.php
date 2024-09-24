<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customerloginfo extends Model
{
    use HasFactory;
    protected $table = 'senders';

    public function calllogs()
    {
        return $this->hasMany(Calllog::class, 'sender_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'sender_id');
    }
}
