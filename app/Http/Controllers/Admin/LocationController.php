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
            'pageTitle' => 'Quản lý khu vực',
            'cities' => $cities,
            'tableReady' => $tableReady,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'Cần chạy php artisan migrate trước khi thêm khu vực.');
        }

        $validated = $this->validateLocationRequest($request, true);

        $districtPayload = $this->buildDistrictPayload($validated['districts']);
        if ($districtPayload === []) {
            return back()->withErrors(['districts' => 'Vui lòng nhập ít nhất một quận/huyện kèm phường/xã hợp lệ.'])->withInput();
        }

        LocationCity::create([
            'name' => trim($validated['name']),
            'districts' => $districtPayload,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Đã thêm thành phố mới.');
    }

    public function update(Request $request, LocationCity $location): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'Cần chạy php artisan migrate trước khi cập nhật khu vực.');
        }

        $validated = $this->validateLocationRequest($request, false, $location->id);

        $districtPayload = $this->buildDistrictPayload($validated['districts']);
        if ($districtPayload === []) {
            return back()->withErrors(['districts' => 'Vui lòng nhập ít nhất một quận/huyện kèm phường/xã hợp lệ.'])->withInput();
        }

        $location->update([
            'name' => trim($validated['name']),
            'districts' => $districtPayload,
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Đã cập nhật thành phố.');
    }

    public function destroy(LocationCity $location): RedirectResponse
    {
        if (!Schema::hasTable('location_cities')) {
            return back()->with('error', 'Cần chạy php artisan migrate trước khi xóa khu vực.');
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Đã xóa thành phố.');
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
            'name.required' => 'Vui lòng nhập tên thành phố.',
            'name.unique' => 'Thành phố này đã tồn tại.',
            'districts.required' => 'Vui lòng thêm ít nhất một quận/huyện.',
            'districts.min' => 'Vui lòng thêm ít nhất một quận/huyện.',
            'districts.*.name.required' => 'Tên quận/huyện không được để trống.',
            'districts.*.wards.required' => 'Vui lòng nhập danh sách phường/xã.',
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
