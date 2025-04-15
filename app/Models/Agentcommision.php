<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentcommision extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function boxtype(){
        return $this->belongsTo(Boxtype::class);
    }
    public function zone(){
        return $this->belongsTo(Zone::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
