<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'is_featured'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Doctor::class, 'specialty_id', 'doctor_id');
    }
}
