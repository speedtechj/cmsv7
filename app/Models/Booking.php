<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $casts = [
        'total_price' => MoneyCast::class,
        'payment_balance' => MoneyCast::class,
        'extracharge_amount' => MoneyCast::class,
         ];
    protected static function booted()
    {
        
        static::creating(function ($invoice) {

            $lastbooking = Booking::orderBy('booking_invoice', 'desc')->first();
            $invoice->booking_invoice = $lastbooking ? $lastbooking->booking_invoice + 1 : 1;
            $invoice->booking_invoice =  str_pad($invoice->booking_invoice, 7, '0', STR_PAD_LEFT);


           
            // $invprefix = Storecode::get()->first()->storecode;
            // // Custom invoice number generation logic, e.g., adding a prefix or suffix
            // $lastbooking = Booking::orderBy('booking_invoice', 'desc')->first();
            // // dd($lastbooking->booking_invoice);
            // $invoicetrim = substr($lastbooking->booking_invoice,2);
            // $invoice->booking_invoice = $lastbooking ? intval($invoicetrim) + 1 : 1;
            // $invoice->booking_invoice =   $invprefix.str_pad($invoice->booking_invoice, 7, '0', STR_PAD_LEFT);
        });
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function agentdiscount()
    {
        return $this->belongsTo(Agentdiscount::class);
    }
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
    // public function transaction()
    // {
    //     return $this->belongsTo(Transaction::class, 'booking_id');
    // }
    public function bookingpayment()
    {
        return $this->hasMany(Bookingpayment::class);
    }
    // public function bookingrefund()
    // {
    //     return $this->hasMany(Bookingrefund::class);
    // }
    public function packinglist()
    {
        return $this->hasMany(packinglist::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function scopeSearchinvoice($query, $search)
    {
        return $query->where('id', $search)->first();
    }

}
