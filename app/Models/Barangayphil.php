<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangayphil extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cityphil(){
        return $this->belongsTo(Cityphil::class);
    }
    // public function receiveraddress(){
    //     return $this->belongsTo(Receiveraddress::class);
    // }
    public function scopeBarangaydisplay($query, $barangayid){
        $barangay_id = Receiveraddress::where('id', $barangayid)->first()->barangayphil_id;
        return $query->where('id', $barangay_id)->first()->name;
    }
}
