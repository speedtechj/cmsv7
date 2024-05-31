<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packinglist extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'packlistdoc' => 'array',
        'waiverdoc' => 'array',
        'packlistitem' => 'array',
    ];
    public function packlistitem(){
        return $this->belongsTo(Packlistitem::class);
    }
    public function booking(){
        return $this->belongsTo(Booking::class);
    }
    public function sender(){
        return $this->belongsTo(Sender::class);
    }

}
