<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty_id',
        'name',
        'email',
        'phone',
        'degree',
        'doctor_dob',
        'citizen_id',
        'citizen_id_front',
        'citizen_id_back',
        'degree_image',
        'license_number',
        'experience_years',
        'hospital',
        'clinic_address',
        'city',
        'bio',
        'consultation_fee',
        'schedule_text',
        'status',
        'is_featured',
        'approval_status',
        'approval_note',
        'verification_status',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'doctor_dob' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function getDegreeListAttribute(): array
    {
        return collect(explode(',', (string) $this->degree))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();
    }

    public function getDegreeDisplayAttribute(): ?string
    {
        $degrees = $this->degree_list;

        return empty($degrees) ? null : implode(', ', $degrees);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    public function reviews()
    {
        return $this->hasMany(AppointmentReview::class, 'doctor_id');
    }
}
