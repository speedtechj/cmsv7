<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shippingbooking extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'bl_attachments' => 'array',
        'telex_attachments' => 'array',
    ];
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function shippingagent()
    {
        return $this->belongsTo(Shippingagent::class);
    }
    public function shippingcontainer(){
        return $this->hasMany(Shippingcontainer::class);
    }
    public function batch(){
        return $this->belongsTo(Batch::class);
    }
}
