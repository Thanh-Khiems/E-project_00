<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Degree extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const DEFAULT_DEGREES = [
        [
            'name' => 'General Practitioner',
            'description' => 'General medical degree for primary care practice.',
            'status' => self::STATUS_ACTIVE,
        ],
        [
            'name' => 'Master',
            'description' => "Master's degree in a medical specialty.",
            'status' => self::STATUS_ACTIVE,
        ],
        [
            'name' => 'Doctorate',
            'description' => 'Doctoral degree in a medical specialty.',
            'status' => self::STATUS_ACTIVE,
        ],
    ];

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public static function defaultDefinitions(): array
    {
        return self::DEFAULT_DEGREES;
    }

    public static function allNames(bool $onlyActive = false): array
    {
        if (! Schema::hasTable('degrees')) {
            return array_column(
                array_filter(self::defaultDefinitions(), fn (array $degree) => ! $onlyActive || $degree['status'] === self::STATUS_ACTIVE),
                'name'
            );
        }

        $query = self::query()->orderBy('name');

        if ($onlyActive) {
            $query->active();
        }

        return $query->pluck('name')->filter()->values()->all();
    }

    public static function normalizeSelected(array $selected, bool $onlyActive = true): array
    {
        $availableNames = collect(self::allNames($onlyActive));
        $selectedLookup = collect($selected)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique();

        return $availableNames
            ->filter(fn (string $name) => $selectedLookup->contains($name))
            ->values()
            ->all();
    }
}
