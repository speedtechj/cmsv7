<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calllog extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function calltype()
    {
        return $this->belongsTo(Calltype::class);
    }
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
}
