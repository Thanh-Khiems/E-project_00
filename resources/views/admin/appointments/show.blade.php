@extends('admin.layouts.app')

@section('content')
<div class="panel-card mb-4">
    <div class="panel-head">
        <div>
            <h5>Chi tiết lịch hẹn {{ $appointment->appointment_code }}</h5>
            <p>Theo dõi buổi khám, chẩn đoán của bác sĩ và toàn bộ toa thuốc đã phát hành cho cuộc hẹn này.</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary">Quay lại danh sách</a>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="border rounded-4 p-4 h-100 bg-white">
                <h6 class="mb-3">Thông tin lịch khám</h6>
                <table class="table mb-0">
                    <tr><th width="35%">Mã hẹn</th><td>{{ $appointment->appointment_code }}</td></tr>
                    <tr><th>Ngày khám</th><td>{{ optional($appointment->appointment_date)->format('d/m/Y') }}</td></tr>
                    <tr><th>Khung giờ</th><td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
                    <tr><th>Địa điểm</th><td>{{ $appointment->location ?? '—' }}</td></tr>
                    <tr><th>Hình thức</th><td>{{ $appointment->type === 'online' ? 'Online' : 'Trực tiếp' }}</td></tr>
                    <tr><th>Trạng thái</th><td>{{ ucfirst($appointment->status) }}</td></tr>
                    <tr><th>Chẩn đoán</th><td>{{ $appointment->diagnosis ?: 'Chưa có chẩn đoán.' }}</td></tr>
                    <tr><th>Lời dặn</th><td>{{ $appointment->doctor_advice ?: 'Chưa có lời dặn.' }}</td></tr>
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
                    <tr><th>Số toa thuốc</th><td>{{ $appointment->prescriptions->count() }}</td></tr>
                    <tr><th>Hoàn tất lúc</th><td>{{ optional($appointment->completed_at)->format('d/m/Y H:i') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel-card mt-4">
        <div class="panel-head">
            <div>
                <h5>Toa thuốc của cuộc hẹn này</h5>
                <p>Một cuộc hẹn có thể phát hành nhiều toa thuốc.</p>
            </div>
        </div>

        @forelse($appointment->prescriptions as $prescription)
            <div class="border rounded-4 p-4 mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong class="text-primary">{{ $prescription->prescription_code }}</strong>
                        <div class="text-muted small mt-1">Phát hành: {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? '—' }}</div>
                    </div>
                    <div class="text-muted">Trạng thái: {{ ucfirst($prescription->status) }}</div>
                </div>
                <div class="mt-3"><strong>Chẩn đoán:</strong> {{ $prescription->diagnosis ?: '—' }}</div>
                <div class="mt-2"><strong>Lời dặn:</strong> {{ $prescription->advice ?: '—' }}</div>
                <div class="mt-2"><strong>Ghi chú toa thuốc:</strong> {{ $prescription->notes ?: '—' }}</div>

                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Thuốc</th>
                                <th>Liều dùng</th>
                                <th>Tần suất</th>
                                <th>Thời gian dùng</th>
                                <th>Số lượng</th>
                                <th>Hướng dẫn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->items as $item)
                                <tr>
                                    <td>{{ $item->medication->name ?? '—' }}<div class="text-muted small">{{ $item->medication->dosage ?? '' }}</div></td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->frequency }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->instructions ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">Buổi khám này chưa có toa thuốc nào.</p>
        @endforelse
    </div>

    <div class="panel-card mt-4">
        <div class="panel-head">
            <div>
                <h5>Lịch sử khám bệnh đã hoàn tất của bệnh nhân</h5>
                <p>Admin có thể xem lại những cuộc khám trước đây và các toa thuốc đã từng cấp.</p>
            </div>
        </div>

        @forelse($patientHistory as $history)
            <div class="border rounded-4 p-4 mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong>{{ $history->appointment_code }}</strong>
                        <div class="text-muted small">{{ optional($history->appointment_date)->format('d/m/Y') }} · {{ $history->doctor->name ?? '—' }}</div>
                    </div>
                    <div class="text-muted">{{ $history->prescriptions->count() }} toa thuốc</div>
                </div>
                <div class="mt-3"><strong>Chẩn đoán:</strong> {{ $history->diagnosis ?: '—' }}</div>
                <div class="mt-2"><strong>Lời dặn:</strong> {{ $history->doctor_advice ?: '—' }}</div>
            </div>
        @empty
            <p class="text-muted mb-0">Không có lịch sử khám hoàn tất nào khác cho bệnh nhân này.</p>
        @endforelse
    </div>
</div>
@endsection
