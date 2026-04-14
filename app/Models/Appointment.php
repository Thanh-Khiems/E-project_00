<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'appointment_day',
        'start_time',
        'end_time',
        'type',
        'location',
        'max_patients',
        'status',
        'notes',
        'diagnosis',
        'doctor_advice',
        'completed_at',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function getAppointmentCodeAttribute(): string
    {
        return 'APP-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class)->latest();
    }
}
