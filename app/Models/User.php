<?php

namespace App\Models;

use App\Models\Patient;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'gender',
        'province',
        'district',
        'ward',
        'address_detail',
        'dob',
        'avatar',
        'role',
        'doctor_verification_status',
        'doctor_verified_at',
        'doctor_rejection_reason',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function patientProfile()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    public function doctorProfile()
    {
        return $this->hasOne(Doctor::class);
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function appointmentReviews()
    {
        return $this->hasMany(AppointmentReview::class, 'patient_id');
    }
}
