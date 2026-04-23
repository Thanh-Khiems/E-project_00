<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Degree extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const FIXED_DEGREES = [
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

    public static function fixedDefinitions(): array
    {
        return self::FIXED_DEGREES;
    }

    public static function fixedNames(): array
    {
        return array_column(self::FIXED_DEGREES, 'name');
    }

    public static function fixedCollection(): Collection
    {
        return collect(self::fixedDefinitions())->values()->map(function (array $degree, int $index) {
            return (object) array_merge(['id' => $index + 1], $degree);
        });
    }

    public static function normalizeSelected(array $selected): array
    {
        $selectedLookup = collect($selected)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique();

        return collect(self::fixedNames())
            ->filter(fn (string $name) => $selectedLookup->contains($name))
            ->values()
            ->all();
    }
}
