<?php

namespace App\Services;

use App\Models\LocationCity;
use Illuminate\Support\Facades\Schema;
use Throwable;

class LocationService
{
    public function getStructuredLocations(): array
    {
        try {
            if (!Schema::hasTable('location_cities')) {
                return $this->normalizeLocations(config('locations', []));
            }

            $cities = LocationCity::query()
                ->orderBy('name')
                ->get(['name', 'districts']);

            if ($cities->isEmpty()) {
                return [];
            }

            $locations = [];
            foreach ($cities as $city) {
                $locations[$city->name] = $this->normalizeDistricts($city->districts ?? []);
            }

            return $locations;
        } catch (Throwable $e) {
            return $this->normalizeLocations(config('locations', []));
        }
    }

    public function getProvinces(): array
    {
        return array_keys($this->getStructuredLocations());
    }

    public function isValidSelection(?string $province, ?string $district, ?string $ward): bool
    {
        if (!$province || !$district || !$ward) {
            return false;
        }

        $locations = $this->getStructuredLocations();

        if (!isset($locations[$province])) {
            return false;
        }

        if (!isset($locations[$province][$district])) {
            return false;
        }

        return in_array($ward, $locations[$province][$district], true);
    }

    public function normalizeDistricts(array $districts): array
    {
        $normalized = [];

        foreach ($districts as $districtName => $wards) {
            $districtName = trim((string) $districtName);
            if ($districtName === '') {
                continue;
            }

            $wardList = array_values(array_unique(array_filter(array_map(
                static fn ($ward) => trim((string) $ward),
                is_array($wards) ? $wards : []
            ))));

            if ($wardList === []) {
                continue;
            }

            sort($wardList, SORT_NATURAL | SORT_FLAG_CASE);
            $normalized[$districtName] = $wardList;
        }

        ksort($normalized, SORT_NATURAL | SORT_FLAG_CASE);

        return $normalized;
    }

    private function normalizeLocations(array $locations): array
    {
        $normalized = [];

        foreach ($locations as $cityName => $districts) {
            $cityName = trim((string) $cityName);
            if ($cityName === '') {
                continue;
            }

            $normalized[$cityName] = $this->normalizeDistricts(is_array($districts) ? $districts : []);
        }

        ksort($normalized, SORT_NATURAL | SORT_FLAG_CASE);

        return $normalized;
    }
}
