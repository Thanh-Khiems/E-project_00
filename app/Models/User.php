<?php

namespace App\Models;

use App\Models\Patient;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function getAvatarUrlAttribute(): string
    {
        $avatar = trim((string) ($this->avatar ?? ''));

        if ($avatar === '') {
            return asset('images/default-avatar.png');
        }

        if (Str::startsWith($avatar, ['http://', 'https://'])) {
            return $avatar;
        }

        $normalized = str_replace('\\', '/', ltrim($avatar, '/'));

        $publicCandidates = [
            $normalized,
            'uploads/avatars/' . basename($normalized),
            'storage/' . ltrim(Str::after($normalized, 'storage/'), '/'),
            'storage/avatars/' . basename($normalized),
            'images/' . basename($normalized),
        ];

        foreach (array_unique($publicCandidates) as $relativePath) {
            $absolutePath = public_path($relativePath);

            if (is_file($absolutePath)) {
                return asset($relativePath) . '?v=' . filemtime($absolutePath);
            }
        }

        if (Storage::disk('public')->exists($normalized) && is_file(public_path('storage/' . $normalized))) {
            $relativePath = 'storage/' . $normalized;
            return asset($relativePath) . '?v=' . filemtime(public_path($relativePath));
        }

        if (Storage::disk('public')->exists('avatars/' . $normalized) && is_file(public_path('storage/avatars/' . $normalized))) {
            $relativePath = 'storage/avatars/' . $normalized;
            return asset($relativePath) . '?v=' . filemtime(public_path($relativePath));
        }

        return asset('images/default-avatar.png');
    }

}

