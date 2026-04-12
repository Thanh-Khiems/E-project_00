<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'start_date',
        'end_date',
        'type',
        'days',
        'start_time',
        'end_time',
        'max_patients',
        'location',
        'notes',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
