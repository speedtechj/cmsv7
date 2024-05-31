<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function Sender()
    {
        return $this->belongsTo(Sender::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function receiveraddress()
    {
        return $this->hasMany(Receiveraddress::class);
    }
    public function scopeReceiverlist($query, $senderid){
        return $query->where('sender_id', $senderid)->get()->pluck('full_name', 'id');
    }
    
}
