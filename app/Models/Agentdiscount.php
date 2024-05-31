<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agentdiscount extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'discount_amount' => MoneyCast::class,
    ];
    public function servicetype(){
        return $this->belongsTo(Servicetype::class);
    }
    public function boxtype(){
        return $this->belongsTo(Boxtype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopeAgentdiscountlist($query,$zoneid,$agentid,$servicetypeid,$boxtypeid)
    {
       return $query->where('zone_id', $zoneid)
         ->where('is_active', true)
         ->where('agent_id',$agentid)
         ->where('servicetype_id', $servicetypeid)
         ->where('boxtype_id', $boxtypeid)
         ->get()->pluck('code', 'id');
         
   
     }
     public function scopeAgentdiscountamount($query, $discountid){
        return $query->where('id', $discountid)->first()->discount_amount;
    }
}
