<?php

namespace App\Models;

use App\Models\Scopes\Activebatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// #[ScopedBy([Activebatch::class])]
class Batch extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function scopeBatchmanifest($query){
        return $query->where('is_active', 1)->get()->pluck('batchno', 'id');
    }

    public function scopeCurrentyear($query)
    {
        return $query->where('batch_year', date('Y'))->get()->pluck('batchno', 'id');
        
    }
}
