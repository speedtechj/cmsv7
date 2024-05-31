<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'filedoc' => 'array',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function citycan(){
        return $this->belongsTo(Citycan::class);
    }
    public function provincecan(){
        return $this->belongsTo(Provincecan::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }

    public function scopeAgentlist($query){
       return $query->select('full_name','id')->pluck('full_name', 'id');
    }
    public function scopeAgenttype($query, $agentid){
        return $query->where('id', $agentid)->first()->agent_type ?? 1;
    }
}
