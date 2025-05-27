<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Node\Block\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Freight extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::deleted(function ( $file) {
            // dd('test');
            if($file->invoices_attachment ){
                Storage::disk('public')->delete($file->invoices_attachment);
            }
           
        });
    }
    protected $casts = [
        'invoices_attachment' => 'array',   
    ];
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function freightitem(){
        return $this->hasMany(Freightitem::class);
    }
    
}
