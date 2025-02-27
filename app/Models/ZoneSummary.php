<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneSummary extends Model
{
    use HasFactory;
    
    protected $table = 'bookings';
    protected $guarded = [];
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
    public function receiveraddress() {
        return $this->belongsTo(Receiveraddress::class);
    
    }
    public function provincephil()
    {
        return $this->belongsTo(Provincephil::class);
    }
    public function cityphil()
    {
        return $this->belongsTo(Cityphil::class);
    }

}
