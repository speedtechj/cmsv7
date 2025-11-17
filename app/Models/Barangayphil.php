<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangayphil extends Model
{
    use HasFactory;
    protected $guarded = [];
public function zoneroute(){
        return $this->belongsTo(Zoneroute::class);
    }
    public function cityphil(){
        return $this->belongsTo(Cityphil::class);
    }

    public function province()
    {
        return $this->hasOneThrough(
            Provincephil::class, // final model you want to reach
            Cityphil::class,     // intermediate model
            'id',            // Foreign key on cities table (City primary key)
            'id',            // Foreign key on provinces table (Province primary key)
            'cityphil_id',       // Local key on barangays table
            'provincephil_id'    // Local key on cities table
        );
    }

    // public function receiveraddress(){
    //     return $this->belongsTo(Receiveraddress::class);
    // }
    public function scopeBarangaydisplay($query, $barangayid){
        $barangay_id = Receiveraddress::where('id', $barangayid)->first()->barangayphil_id;
        return $query->where('id', $barangay_id)->first()->name;
    }
}
