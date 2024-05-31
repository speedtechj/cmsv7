<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'discount_amount' => MoneyCast::class,
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function servicetype(){
        return $this->belongsTo(Servicetype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function boxtype(){
        return $this->belongsTo(Boxtype::class);
    }
    // public function booking(){
    //     return $this->belongsTo(Booking::class);
    // }
        public function scopeOfficediscount($query,$zoneid,$servicetypeid,$boxtypeid){
           return $query->where('zone_id', $zoneid)
            ->where('is_active', true)
            ->where('servicetype_id', $servicetypeid)
            ->where('boxtype_id', $boxtypeid)
            ->get()->pluck('code', 'id');
      
        }

public function scopeDiscountamount($query, $discountid){
    return $query->where('id', $discountid)->first()->discount_amount;
}
}
