<?php

namespace App\Models;

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

        public function doctorProfile()
    {
        return $this->hasOne(Doctor::class);
    }
}
