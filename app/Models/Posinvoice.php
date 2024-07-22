<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posinvoice extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
{
    static::creating(function ($refinv) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastref = Posinvoice::orderBy('invoice_no', 'desc')->first();
        $refinv->invoice_no = $lastref ? $lastref->invoice_no + 1 : 1;
        $refinv->invoice_no =  str_pad($refinv->invoice_no, 6, '0', STR_PAD_LEFT);
    });
}
    public function senders() :BelongsTo{
        return $this->belongsTo(Sender::class, 'sender_id');
    }
    public function purchaseitems() :HasMany {
        return $this->hasMany(Purchaseitem::class);
    }
}
