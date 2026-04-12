<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_code',
        'name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function syncFromUser(User $user): self
    {
        $patient = static::firstOrNew(['user_id' => $user->id]);

        $patient->fill([
            'name' => $user->full_name,
            'date_of_birth' => $user->dob,
            'gender' => $user->gender ?? 'other',
            'phone' => $user->phone,
            'email' => $user->email,
            'address' => collect([
                $user->address_detail,
                $user->ward,
                $user->district,
                $user->province,
            ])->filter()->implode(', '),
        ]);

        $patient->save();

        if (! $patient->patient_code) {
            $patient->update([
                'patient_code' => 'BN-' . str_pad((string) $patient->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return $patient->fresh();
    }
}
