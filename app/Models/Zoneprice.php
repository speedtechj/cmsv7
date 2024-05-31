<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zoneprice extends Model
{
    use HasFactory;
    protected $casts = [
        'price' => MoneyCast::class,
    ];
    protected $guarded = [];

    public function servicetype(){
        return $this->belongsTo(Servicetype::class);
    }
    public function boxtype(){
        return $this->belongsTo(Boxtype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }

    public function scopeOfficeprice($query, $serviceid = 'null', $zoneid = 'null', $boxtypeid = 'null', $quantity = 'null'){
        return $query->whereHas('servicetype', function ($query) use ($serviceid) {
                $query->where('servicetype_id', $serviceid);
            })
                ->whereHas('zone', function ($query) use ($zoneid) {
                    $query->where('zone_id', $zoneid);
                })
                ->whereHas('boxtype', function ($query) use ($boxtypeid) {
                    $query->where('boxtype_id', $boxtypeid);
                })
                ->whereHas('branch', function ($query) {
                    $query->where('branch_id', auth()->user()->branch_id); //default branch id
                })
                ->first()->price ?? 0;
    }
}
