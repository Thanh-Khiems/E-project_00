@php
    use App\Models\Medication;
    use App\Models\MedicineType;

    $medicines = Medication::with('type')->get();
    $medicineTypes = MedicineType::all();
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loại thuốc và thuốc</title>
    <link rel="stylesheet" href="{{ asset('css/medications.css') }}">
</head>
<body>

<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon">✚</div>
        <div>
            <h1>Clinical Sanctuary</h1>
            <p>Pharmacy Management</p>
        </div>
    </div>

    <nav class="menu">
        <a href="#">Dashboard</a>
        <a href="#" class="active">Loại thuốc</a>
    </nav>
</aside>

<header class="topbar">
    <div class="search-box">
        <input type="text" id="globalSearch" placeholder="Tìm kiếm thuốc...">
    </div>
</header>

<main class="main">

<section class="page-header">
    <div>
        <h2>Hệ thống Quản trị Dược phẩm</h2>
        <p>Theo dõi và quản lý danh mục thuốc</p>
    </div>
</section>

<section class="stats">
    <div class="stat-card">
        <p>Tổng loại thuốc</p>
        <h3>{{ $medicineTypes->count() }}</h3>
    </div>
    <div class="stat-card">
        <p>Tổng thuốc</p>
        <h3>{{ $medicines->count() }}</h3>
    </div>
</section>

<section class="tabs">
    <button class="tab-btn active" data-tab="medicinesTab">Danh sách thuốc</button>
    <button class="tab-btn" data-tab="typesTab">Loại thuốc</button>
    <button class="tab-btn" data-tab="formTab">Thêm mới</button>
</section>

<!-- ================= DANH SÁCH ================= -->
<section class="panel active" id="medicinesTab">
    <div class="panel-toolbar">
        <input type="text" id="medicineSearch" placeholder="Tìm theo tên thuốc...">

        <select id="typeFilter">
            <option value="">Loại thuốc</option>
            @foreach ($medicineTypes as $type)
                <option value="{{ $type->name }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tên thuốc</th>
                    <th>Loại</th>
                    <th>Hàm lượng</th>
                    <th class="text-right">Thao tác</th>
                </tr>
            </thead>

            <tbody id="medicineTable">
                @foreach ($medicines as $medicine)
                <tr data-type="{{ optional($medicine->type)->name }}">
                    <td>
                        <div class="medicine-cell">
                            <span>{{ $medicine->name }}</span>
                        </div>
                    </td>

                    <td>
                        <span class="pill">
                            {{ optional($medicine->type)->name ?? 'Chưa có' }}
                        </span>
                    </td>

                    <td class="ingredient">
                        <small>{{ $medicine->dosage }}</small>
                    </td>

                    <td class="text-right actions">

                        <!-- DELETE -->
                        <form action="/medications/{{ $medicine->id }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn danger">Xóa</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<!-- ================= LOẠI THUỐC ================= -->
<section class="panel" id="typesTab">
    <div class="types-header">
        <h3>Danh sách loại thuốc</h3>
    </div>

    <div class="type-grid">
        @foreach ($medicineTypes as $type)
        <div class="type-card">
            <h4>{{ $type->name }}</h4>
            <p>{{ $type->description }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- ================= FORM ================= -->
<section class="panel" id="formTab">
    <div class="form-grid">

        <!-- THÊM LOẠI -->
        <div class="form-card">
            <h3>Thêm loại thuốc</h3>

            <form action="/medicine-types" method="POST">
                @csrf

                <div class="form-group">
                    <label>Tên loại thuốc</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="description"></textarea>
                </div>

                <button class="primary-btn small">Lưu</button>
            </form>
        </div>

        <!-- THÊM THUỐC -->
        <div class="form-card">
            <h3>Thêm thuốc</h3>

            <form action="/medications" method="POST">
                @csrf

                <div class="form-group">
                    <label>Tên thuốc</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Loại thuốc</label>
                    <select name="medicine_type_id" required>
                        <option value="">-- chọn loại --</option>
                        @foreach ($medicineTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Hàm lượng</label>
                    <input type="text" name="dosage" required>
                </div>

                <div class="form-group">
                    <label>Danh mục</label>
                    <input type="text" name="category">
                </div>

                <button class="primary-btn small">Lưu</button>
            </form>
        </div>

    </div>
</section>

</main>

<script src="{{ asset('js/medications.js') }}"></script>

</body>
</html>