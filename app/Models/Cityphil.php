<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cityphil extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function provincephil(){
        return $this->belongsTo(Provincephil::class);
    }
    public function barangayphil(){
        return $this->hasMany(Barangayphil::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function scopeCitydisplay($query, $cityid){
        $city_id = Receiveraddress::where('id', $cityid)->first()->cityphil_id;
        return $query->where('id', $city_id)->first()->name;
    }
}
