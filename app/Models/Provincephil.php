<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincephil extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function cityphil(){
        return $this->hasMany(Cityphil::class);
    }

    public function scopeProvincedisplay($query, $provinceid){
        $province_id = Receiveraddress::where('id', $provinceid)->first()->provincephil_id;
        return $query->where('id', $province_id)->first()->name;
    }
}
