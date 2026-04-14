@extends('admin.layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel-card">
            <div class="panel-head">
                <div>
                    <h5>Thêm thuốc mới</h5>
                    <p>Admin quản lý danh mục thuốc, bác sĩ chỉ sử dụng danh mục này để kê đơn.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.medications.store') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên thuốc</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hàm lượng / dạng dùng</label>
                    <input type="text" name="dosage" class="form-control" placeholder="500mg, siro 60ml..." required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nhóm thuốc</label>
                    <select name="medicine_type_id" class="form-select">
                        <option value="">-- Chọn nhóm thuốc --</option>
                        @foreach($medicineTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phân loại</label>
                    <input type="text" name="category" class="form-control" placeholder="Kháng sinh, giảm đau...">
                </div>
                <button type="submit" class="btn btn-primary">Thêm thuốc</button>
            </form>

            <hr class="my-4">

            <h6>Thêm nhóm thuốc</h6>
            <form method="POST" action="{{ route('admin.medicine-types.store') }}" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên nhóm thuốc</label>
                    <input type="text" name="name" class="form-control" placeholder="Kháng sinh" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="3" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-outline-primary">Thêm nhóm thuốc</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-head">
                <div>
                    <h5>Danh mục thuốc</h5>
                    <p>Kho thuốc chuẩn để bác sĩ lựa chọn khi kê đơn.</p>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tên thuốc</th>
                            <th>Hàm lượng</th>
                            <th>Nhóm thuốc</th>
                            <th>Phân loại</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medications as $medication)
                            <tr>
                                <td>{{ $medication->name }}</td>
                                <td>{{ $medication->dosage }}</td>
                                <td>{{ $medication->medicineType->name ?? '—' }}</td>
                                <td>{{ $medication->category ?? '—' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.medications.destroy', $medication) }}" onsubmit="return confirm('Xóa thuốc này khỏi danh mục?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Chưa có thuốc nào trong danh mục.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $medications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
