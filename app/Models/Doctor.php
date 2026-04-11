<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialty_id',
        'name',
        'email',
        'phone',
        'experience_years',
        'schedule_text',
        'status',
        'is_featured',
        'approval_status',
        'approval_note',
        'approved_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
