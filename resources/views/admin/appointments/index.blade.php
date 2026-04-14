@extends('admin.layouts.app')

@section('content')
<div class="panel-card mb-4">
    <div class="panel-head">
        <div>
            <h5>Lịch hẹn toàn hệ thống</h5>
            <p>Theo dõi trạng thái lịch khám, số toa thuốc đã phát hành và lịch sử các cuộc khám đã diễn ra.</p>
        </div>
        <a href="{{ route('admin.medications.index') }}" class="btn btn-primary">Quản lý danh mục thuốc</a>
    </div>

    <form method="GET" class="row g-3 filter-bar mt-3">
        <div class="col-md-4"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Mã hẹn, bệnh nhân, bác sĩ..."></div>
        <div class="col-md-3">
            <select name="doctor_id" class="form-select">
                <option value="">-- Tất cả bác sĩ --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ (string) request('doctor_id') === (string) $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Tất cả trạng thái --</option>
                @foreach(['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Lọc</button></div>
    </form>
</div>

<div class="panel-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Mã hẹn</th>
                    <th>Bệnh nhân</th>
                    <th>Bác sĩ</th>
                    <th>Ngày khám</th>
                    <th>Trạng thái</th>
                    <th>Chẩn đoán</th>
                    <th>Toa thuốc</th>
                    <th>Đánh giá</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td><strong>{{ $appointment->appointment_code }}</strong></td>
                        <td>
                            {{ $appointment->patient->full_name ?? '—' }}
                            <div class="text-muted small">{{ $appointment->patient->email ?? '—' }}</div>
                        </td>
                        <td>
                            {{ $appointment->doctor->name ?? '—' }}
                            <div class="text-muted small">{{ optional($appointment->doctor->specialty)->name ?? '—' }}</div>
                        </td>
                        <td>
                            {{ optional($appointment->appointment_date)->format('d/m/Y') ?? '—' }}
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'confirmed' ? 'primary' : ($appointment->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($appointment->diagnosis ?: 'Chưa có', 60) }}</td>
                        <td>{{ $appointment->prescriptions->count() }}</td>
                        <td>
                            @if($appointment->review)
                                <div class="fw-semibold text-warning">{{ str_repeat('★', (int) $appointment->review->rating) }}</div>
                                <div class="text-muted small">{{ $appointment->review->rating }}/5 · {{ $appointment->review->patient->full_name ?? 'Bệnh nhân' }}</div>
                            @else
                                <span class="text-muted small">Chưa có</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Không có lịch hẹn phù hợp.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
