<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companyinfo extends Model {
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'company_logo' => 'array',
    ];
}
