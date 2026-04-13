@extends('admin.layouts.app')

@section('content')
<div class="panel-card mb-4">
    <div class="panel-head">
        <div>
            <h5>Chi tiết lịch hẹn {{ $appointment->appointment_code }}</h5>
            <p>Theo dõi đầy đủ thông tin lịch đặt khám, bệnh nhân, bác sĩ và trạng thái xử lý.</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary">Quay lại danh sách</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="border rounded-4 p-4 h-100 bg-white">
                <h6 class="mb-3">Thông tin lịch hẹn</h6>
                <table class="table mb-0">
                    <tr><th width="35%">Mã hẹn</th><td>{{ $appointment->appointment_code }}</td></tr>
                    <tr><th>Ngày khám</th><td>{{ optional($appointment->appointment_date)->format('d/m/Y') }}</td></tr>
                    <tr><th>Thứ</th><td>{{ $appointment->appointment_day ?? '—' }}</td></tr>
                    <tr><th>Khung giờ</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
                    <tr><th>Hình thức</th><td>{{ $appointment->type === 'online' ? 'Online' : 'Trực tiếp' }}</td></tr>
                    <tr><th>Địa điểm</th><td>{{ $appointment->location ?? '—' }}</td></tr>
                    <tr><th>Trạng thái</th><td><span class="status-badge {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td></tr>
                    <tr><th>Ghi chú</th><td>{{ $appointment->notes ?: 'Chưa có ghi chú.' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="border rounded-4 p-4 h-100 bg-white">
                <h6 class="mb-3">Thông tin bệnh nhân và bác sĩ</h6>
                <table class="table mb-0">
                    <tr><th width="35%">Bệnh nhân</th><td>{{ $appointment->patient->full_name ?? '—' }}</td></tr>
                    <tr><th>Email bệnh nhân</th><td>{{ $appointment->patient->email ?? '—' }}</td></tr>
                    <tr><th>Số điện thoại</th><td>{{ $appointment->patient->phone ?? '—' }}</td></tr>
                    <tr><th>Bác sĩ</th><td>{{ $appointment->doctor->name ?? '—' }}</td></tr>
                    <tr><th>Chuyên khoa</th><td>{{ optional($appointment->doctor->specialty)->name ?? '—' }}</td></tr>
                    <tr><th>Email bác sĩ</th><td>{{ $appointment->doctor->email ?? ($appointment->doctor->user->email ?? '—') }}</td></tr>
                    <tr><th>Lịch gốc</th><td>{{ $appointment->schedule ? ('#' . $appointment->schedule->id) : '—' }}</td></tr>
                    <tr><th>Số lượng tối đa</th><td>{{ $appointment->max_patients ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        @if($appointment->status === 'pending')
            <form method="POST" action="{{ route('appointments.confirm', $appointment) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-primary">Xác nhận lịch hẹn</button>
            </form>
        @endif

        @if(in_array($appointment->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger">Hủy lịch hẹn</button>
            </form>
        @endif
    </div>
</div>
@endsection
