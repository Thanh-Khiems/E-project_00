<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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



    public function scopeExpired(Builder $query, ?Carbon $reference = null): Builder
    {
        $reference ??= now();

        return $query
            ->where('status', '!=', 'completed')
            ->where(function (Builder $builder) use ($reference) {
                $builder->whereDate('appointment_date', '<', $reference->toDateString())
                    ->orWhere(function (Builder $sameDayQuery) use ($reference) {
                        $sameDayQuery->whereDate('appointment_date', $reference->toDateString())
                            ->whereTime('end_time', '<', $reference->format('H:i:s'));
                    });
            });
    }

    public function scopeVisibleInCurrentWeek(Builder $query, ?Carbon $reference = null): Builder
    {
        $reference ??= now();
        $end = $reference->copy()->addWeek();

        return $query
            ->where(function (Builder $builder) use ($reference) {
                $builder->whereDate('appointment_date', '>', $reference->toDateString())
                    ->orWhere(function (Builder $sameDayQuery) use ($reference) {
                        $sameDayQuery->whereDate('appointment_date', $reference->toDateString())
                            ->whereTime('end_time', '>=', $reference->format('H:i:s'));
                    });
            })
            ->where(function (Builder $builder) use ($end) {
                $builder->whereDate('appointment_date', '<', $end->toDateString())
                    ->orWhere(function (Builder $sameDayQuery) use ($end) {
                        $sameDayQuery->whereDate('appointment_date', $end->toDateString())
                            ->whereTime('start_time', '<=', $end->format('H:i:s'));
                    });
            });
    }

    public function isExpired(?Carbon $reference = null): bool
    {
        if ($this->status === 'completed') {
            return false;
        }

        $reference ??= now();
        $appointmentDate = Carbon::parse($this->appointment_date)->toDateString();
        $today = $reference->toDateString();

        if ($appointmentDate < $today) {
            return true;
        }

        return $appointmentDate === $today
            && Carbon::parse($this->end_time)->format('H:i:s') < $reference->format('H:i:s');
    }

    public static function purgeExpired(?Carbon $reference = null): int
    {
        return static::query()->expired($reference)->delete();
    }

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

    public function review(): HasOne
    {
        return $this->hasOne(AppointmentReview::class, 'appointment_id');
    }
}
