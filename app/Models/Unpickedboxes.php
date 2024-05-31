<?php

namespace App\Models;

use App\Models\Agent;
use App\Models\Sender;
use App\Models\Boxtype;
use App\Models\Receiver;
use App\Models\Servicetype;
use App\Models\Agentdiscount;
use App\Models\Senderaddress;
use App\Models\Receiveraddress;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\UnpickedboxScope;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[ScopedBy([UnpickedBoxScope::class])]
class Unpickedboxes extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $guarded = [];
    
    public function agentdiscount()
    {
        return $this->belongsTo(Agentdiscount::class);
    }
    public function boxtype()
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function servicetype()
    {
        return $this->belongsTo(Servicetype::class);
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }
    public function senderaddress()
    {
        return $this->belongsTo(Senderaddress::class);
    }
    public function receiveraddress()
    {
        return $this->belongsTo(Receiveraddress::class);
    }
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function zoneprice()
    {
        return $this->belongsTo(Zoneprice::class);
    }
    public function agentprice()
    {
        return $this->belongsTo(Agentprice::class);
    }
    
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    // public function scopeUnpickedbox($query){
    //     return $query->whereDate('booking_date' ,'<=',now())
    //     ->where(function (Builder $query){
    //         $query->where('is_pickup', false)
    //         ->orWhere('is_paid', false);
    //     });
    // }

}
