<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agentprice extends Model
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
    public function agent(){
        return $this->belongsTo(Agent::class);
    }

    public function scopeAgentprice($query, $serviceid = 'null', $zoneid = 'null', $boxtypeid = 'null', $quantity = 'null', $agentid = 'null'){
       return Agentprice::with(['servicetype', 'zone', 'boxtype', 'agent'])
            ->whereHas('servicetype', function ($query) use ($serviceid) {
                $query->where('servicetype_id', $serviceid);
            })
            ->whereHas('zone', function ($query) use ($zoneid) {
                $query->where('zone_id', $zoneid);
            })
            ->whereHas('boxtype', function ($query) use ($boxtypeid) {
                $query->where('boxtype_id', $boxtypeid);
            })
            ->whereHas('agent', function ($query) use ($agentid) {
                $query->where('agent_id', $agentid);
            })
            ->first()->price ?? 0;
     
    }
}
