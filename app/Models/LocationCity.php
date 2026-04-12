<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCity extends Model
{
    protected $fillable = [
        'name',
        'districts',
    ];

    protected $casts = [
        'districts' => 'array',
    ];
}
