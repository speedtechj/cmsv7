<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agentinvoice extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
        
    
}
