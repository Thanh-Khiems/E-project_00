<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationCity;
use App\Services\LocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct(private readonly LocationService $locationService)
    {
    }

    public function index(): View
    {
        $tableReady = Schema::hasTable('location_cities');

        $cities = $tableReady
            ? LocationCity::query()->orderBy('name')->get()
            : collect();

        return view('admin.locations.index', [
            'pageTitle' => 'Location management',
            'cities' => $cities,
            'tableReady' => $tableReady,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'You need to run php artisan migrate before adding locations.');
        }

        $validated = $this->validateLocationRequest($request, true);

        $districtPayload = $this->buildDistrictPayload($validated['districts']);
        if ($districtPayload === []) {
            return back()->withErrors(['districts' => 'Please enter at least one valid district with wards/communes.'])->withInput();
        }

        LocationCity::create([
            'name' => trim($validated['name']),
            'districts' => $districtPayload,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'New city added.');
    }

    public function update(Request $request, LocationCity $location): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'You need to run php artisan migrate before updating locations.');
        }

        $validated = $this->validateLocationRequest($request, false, $location->id);

        $districtPayload = $this->buildDistrictPayload($validated['districts']);
        if ($districtPayload === []) {
            return back()->withErrors(['districts' => 'Please enter at least one valid district with wards/communes.'])->withInput();
        }

        $location->update([
            'name' => trim($validated['name']),
            'districts' => $districtPayload,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'City updated.');
    }

    public function destroy(LocationCity $location): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'You need to run php artisan migrate before deleting locations.');
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'City deleted.');
    }

    private function validateLocationRequest(Request $request, bool $isCreate, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:location_cities,name';
        if (!$isCreate && $ignoreId) {
            $uniqueRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255', $uniqueRule],
            'districts' => ['required', 'array', 'min:1'],
            'districts.*.name' => ['required', 'string', 'max:255'],
            'districts.*.wards' => ['required', 'string'],
        ], [
            'name.required' => 'Please enter a city name.',
            'name.unique' => 'This city already exists.',
            'districts.required' => 'Please add at least one district.',
            'districts.min' => 'Please add at least one district.',
            'districts.*.name.required' => 'District name cannot be empty.',
            'districts.*.wards.required' => 'Please enter the list of wards/communes.',
        ]);
    }

    private function buildDistrictPayload(array $districtRows): array
    {
        $districts = [];

        foreach ($districtRows as $row) {
            $districtName = trim((string) ($row['name'] ?? ''));
            $wardText = (string) ($row['wards'] ?? '');

            if ($districtName === '') {
                continue;
            }

            $wards = preg_split('/[,\r\n]+/u', $wardText) ?: [];
            $districts[$districtName] = array_values(array_unique(array_filter(array_map(
                static fn ($ward) => trim((string) $ward),
                $wards
            ))));
        }

        return $this->locationService->normalizeDistricts($districts);
    }
}
