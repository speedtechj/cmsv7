<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipmentstatus extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function servicetype()
    {
        return $this->belongsTo(Servicetype::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
    public function senderaddress()
    {
        return $this->belongsTo(Senderaddress::class);
    }
    public function receiveraddress()
    {
        return $this->belongsTo(Receiveraddress::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function zoneprice()
    {
        return $this->belongsTo(Zoneprice::class);
    }
    public function agentprice()
    {
        return $this->belongsTo(Agentprice::class);
    }
    public function bookingpayment()
    {
        return $this->hasMany(Bookingpayment::class, 'booking_id');
    }
    
    public function packinglist()
    {
        return $this->hasMany(Packinglist::class, 'booking_id');
    }
    public function invoicestatuses(): HasMany
    {
        return $this->hasMany(Invoicestatus::class, 'booking_id');
    }
    public function provincephil()
    {
        return $this->belongsTo(Provincephil::class);
    }
    public function cityphil()
    {
        return $this->belongsTo(Cityphil::class);
    }
    public function receiveraddresses() {
        return $this->belongsTo(Receiveraddress::class, 'receiveraddress_id');
    
    }
    public function invoicestatus(){
        return $this->hasMany(Invoicestatus::class, 'generated_invoice');

    }
    
}
