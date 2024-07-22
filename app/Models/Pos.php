<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pos extends Model
{
    use HasFactory;
    protected $table = 'senders';
    protected $guarded = [];
    public function posinvoices(){
        return $this->hasMany(Posinvoice::class, 'sender_id');
    }
    

}
