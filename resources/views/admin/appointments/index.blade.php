@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Tổng lịch hẹn', 'value' => $stats['total'], 'icon' => 'bi-calendar2-week'],
        ['label' => 'Hôm nay', 'value' => $stats['today'], 'icon' => 'bi-calendar-day'],
        ['label' => 'Chờ xác nhận', 'value' => $stats['pending'], 'icon' => 'bi-hourglass-split'],
        ['label' => 'Hoàn tất', 'value' => $stats['completed'], 'icon' => 'bi-check2-square'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Lịch hẹn toàn hệ thống</h5>
                <p>Theo dõi, lọc và điều phối lịch khám theo bác sĩ, bệnh nhân, chuyên khoa và trạng thái.</p>
            </div>
            <button class="btn btn-primary">+ Tạo lịch hẹn</button>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-4"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Mã lịch hẹn, bệnh nhân, bác sĩ..."></div>
            <div class="col-md-3">
                <select name="doctor_id" class="form-select">
                    <option value="">Tất cả bác sĩ</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(request('doctor_id') == $doctor->id)>{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                    <option value="confirmed" @selected(request('status') == 'confirmed')>Confirmed</option>
                    <option value="completed" @selected(request('status') == 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') == 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Lọc</button></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Mã hẹn</th>
                        <th>Bệnh nhân</th>
                        <th>Bác sĩ</th>
                        <th>Chuyên khoa</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Điều phối</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td><strong>{{ $appointment->appointment_code ?? 'APP-0000' }}</strong></td>
                            <td>{{ $appointment->patient->name ?? $appointment->patient_name ?? '—' }}</td>
                            <td>{{ $appointment->doctor->name ?? $appointment->doctor_name ?? '—' }}</td>
                            <td>{{ $appointment->specialty->name ?? '—' }}</td>
                            <td>{{ optional($appointment->appointment_date)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td><span class="status-badge {{ $appointment->status }}">{{ ucfirst($appointment->status ?? 'pending') }}</span></td>
                            <td class="text-end table-actions">
                                <a href="#">Xem</a>
                                <a href="#">Dời lịch</a>
                                <a href="#" class="text-danger">Hủy</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4">Chưa có lịch hẹn nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $appointments->links() }}
    </div>
@endsection
