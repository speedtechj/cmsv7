<?php

namespace App\Models;
use App\Models\Scopes\InvdeliverScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Searchinvoice extends Model
{
    use HasFactory;
    protected $table = 'bookings';
   
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
  
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function invoicestatus(){
        return $this->hasMany(Invoicestatus::class,'booking_id');
    }
    public function remarkstatus(){
        return $this->hasMany(Remarkstatus::class,'booking_id');
    }
    public function statuscategory(){
        return $this->belongsTo(Statuscategory::class);
    }
    public function invattach(){
        return $this->hasMany(Invattach::class,'booking_id');
    }
   
}
