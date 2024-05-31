<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boxtype extends Model
{
    use HasFactory;
    protected $guarded = [];
    // public function zoneprice(){
    //     return $this->hasMany(Zoneprice::class);
    // }

    public function scopeTotalbox($query,$boxtypeid){
        return $query->where('id', $boxtypeid)->first()->total_box;
    }
}
