<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchaseitem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function agent() :BelongsTo{
        return $this->belongsTo(Agent::class);
    }

    public function posinvoice() :BelongsTo{
        return $this->belongsTo(Posinvoice::class);
    }
    public function boxtype() :BelongsTo{
        return $this->belongsTo(Boxtype::class);
    }

    // protected $appends = ['total_discount'];

    public function getTotalDiscountAttribute()
    {
        return $this->discount_amount;
    }
    
}
