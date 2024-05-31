<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiveraddress extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function receiver(){
        return $this->belongsTo(Receiver::class);
    }
    public function provincephil(){
        return $this->belongsTo(Provincephil::class);
    }
    public function cityphil(){
        return $this->belongsTo(Cityphil::class);
    }

    public function barangayphil(){
        return $this->belongsTo(Barangayphil::class);
    }

    public function scopeReceiveraddresslist($query, $receiveraddressid){
        return $query->where('receiver_id', $receiveraddressid)->get()->pluck('address', 'id');
    }
    public function scopeZoneid($query, $receiveraddressid){
        return $query->where('id', $receiveraddressid)->get()->first()->cityphil->zone_id;
    }
   
}
