<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pospayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function posinvoice() :BelongsTo
    {
        return $this->belongsTo(Posinvoice::class);
    }

    public function paymenttype() :BelongsTo
    {
        return $this->belongsTo(Paymenttype::class);
    }
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
