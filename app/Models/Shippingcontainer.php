<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingcontainer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function trucker()
    {
        return $this->belongsTo(Trucker::class);
    }
    public function shippingbooking()
    {
        return $this->belongsTo(Shippingbooking::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    public function user ()
    {
        return $this->belongsTo(User::class);
    }
}
