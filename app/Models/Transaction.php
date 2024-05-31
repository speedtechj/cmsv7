<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted()
{
    static::creating(function ($refinv) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastref = Transaction::orderBy('reference_invoice', 'desc')->first();
        $refinv->reference_invoice = $lastref ? $lastref->reference_invoice + 1 : 1;
        $refinv->reference_invoice =  str_pad($refinv->reference_invoice, 6, '0', STR_PAD_LEFT);
    });
}
    public function booking()
    {
        return $this->hasMany(Booking::class, 'booking_invoice');
    }
    public function senderaddress(){
        return $this->belongsTo(Senderaddress::class);
    } 
}
