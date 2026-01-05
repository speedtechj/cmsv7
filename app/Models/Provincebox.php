<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincebox extends Model
{
    use HasFactory;
   protected $table = 'provincephils';

  public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            Receiveraddress::class,
            'provincephil_id',
            'receiveraddress_id',
            'id',
            'id'
        );
    }
   // Province.php
public function receiveraddresses()
{
    return $this->hasMany(Receiveraddress::class, 'provincephil_id');
}


}
