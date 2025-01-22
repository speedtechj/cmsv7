<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batchpackinglist extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
       
        'packinglist_attachment' => 'array',
        
    ];
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
