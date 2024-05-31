<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingagent extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
}
