<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookingutil extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
