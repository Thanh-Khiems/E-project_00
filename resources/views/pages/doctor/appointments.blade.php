@extends('layouts.app')

@section('content')
<style>
    .doctor-appointments-page { background: #f5f7fb; min-height: 100vh; padding: 24px; }
    .doctor-appointments-wrapper { max-width: 1400px; margin: 0 auto; display: grid; grid-template-columns: 260px 1fr; gap: 24px; }
    .doctor-sidebar, .doctor-card, .doctor-stat-card, .doctor-table-wrap, .doctor-header { border-radius: 20px; }
    .doctor-sidebar, .doctor-card, .doctor-stat-card, .doctor-table-wrap { background: #fff; box-shadow: 0 10px 26px rgba(0, 0, 0, 0.05); }
    .doctor-sidebar { padding: 24px 18px; min-height: 780px; display: flex; flex-direction: column; justify-content: space-between; }
    .doctor-brand h2 { color: #1d4ed8; font-size: 22px; font-weight: 800; margin-bottom: 8px; }
    .doctor-brand p { color: #6b7280; font-size: 14px; margin-bottom: 24px; }
    .doctor-menu { display: flex; flex-direction: column; gap: 10px; }
    .doctor-menu a, .doctor-menu button {
        display: block; width: 100%; text-align: left; padding: 12px 14px; border-radius: 12px; text-decoration: none;
        color: #374151; font-weight: 600; border: none; background: transparent; cursor: pointer;
    }
    .doctor-menu a:hover, .doctor-menu button:hover { background: #eff6ff; color: #1d4ed8; }
    .doctor-menu a.active { background: #1d4ed8; color: #fff; }
    .doctor-sidebar-bottom { margin-top: 24px; border-top: 1px solid #e5e7eb; padding-top: 18px; }
    .doctor-main { display: flex; flex-direction: column; gap: 24px; }
    .doctor-header { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: #fff; padding: 28px; box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25); }
    .doctor-header h1 { font-size: 30px; font-weight: 800; margin-bottom: 8px; }
    .doctor-header p { margin: 0; opacity: .95; }
    .doctor-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .doctor-stat-card { padding: 20px; }
    .doctor-stat-card h4 { font-size: 14px; color: #6b7280; margin-bottom: 10px; }
    .doctor-stat-card .stat-number { font-size: 28px; font-weight: 800; color: #111827; }
    .doctor-stat-card .stat-note { color: #10b981; font-size: 13px; }
    .doctor-table-wrap { padding: 24px; overflow: hidden; }
    .doctor-table-wrap h3 { margin-bottom: 16px; font-weight: 800; color: #111827; }
    .doctor-table { width: 100%; border-collapse: collapse; }
    .doctor-table th { text-align: left; background: #eff6ff; color: #1d4ed8; padding: 14px 12px; font-size: 14px; }
    .doctor-table td { padding: 14px 12px; border-bottom: 1px solid #e5e7eb; color: #374151; vertical-align: top; }
    .status-badge { display: inline-flex; align-items: center; padding: 8px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.confirmed { background: #dbeafe; color: #1d4ed8; }
    .status-badge.completed { background: #dcfce7; color: #166534; }
    .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
    .muted { color: #6b7280; font-size: 13px; }
    .empty-state { padding: 32px; text-align: center; color: #6b7280; }
    .pagination-wrap { margin-top: 20px; }
    .flash-box { margin-bottom:16px; padding:12px 14px; border-radius:12px; }
    .flash-box.success { background:#ecfdf5; color:#166534; }
    .flash-box.error { background:#fef2f2; color:#991b1b; }
    .action-cell { min-width: 220px; }
    .action-stack { display: flex; flex-wrap: wrap; gap: 8px; }
    .action-btn {
        display: inline-flex; align-items:center; justify-content:center; min-width: 92px; padding: 9px 12px; border-radius: 10px;
        border: none; font-weight: 700; font-size: 13px; text-decoration:none; cursor:pointer;
    }
    .btn-confirm { background: #dbeafe; color: #1d4ed8; }
    .btn-cancel { background: #fee2e2; color: #b91c1c; }
    .btn-disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
    @media (max-width: 1100px) { .doctor-appointments-wrapper { grid-template-columns: 1fr; } .doctor-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .doctor-stats { grid-template-columns: 1fr; } .doctor-table-wrap { overflow-x: auto; } .doctor-table { min-width: 1200px; } }
</style>

<div class="doctor-appointments-page">
    <div class="doctor-appointments-wrapper">
        <aside class="doctor-sidebar">
            <div>
                <div class="doctor-brand">
                    <h2>MediConnect</h2>
                    <p>Doctor Appointment</p>
                </div>

                <nav class="doctor-menu">
                    <a href="{{ route('doctor.manage') }}">Dashboard</a>
                    <a href="{{ route('doctor.manage', ['tab' => 'schedule']) }}">Schedule Settings</a>
                    <a href="{{ route('doctor.appointments') }}" class="active">Appointments</a>
                </nav>
            </div>

            <div class="doctor-sidebar-bottom">
                <strong>Help Center</strong>
                <form method="POST" action="{{ route('logout') }}" style="margin-top: 18px;">
                    @csrf
                    <button type="submit" style="color:#ef4444;font-weight:700;">Logout</button>
                </form>
            </div>
        </aside>

        <main class="doctor-main">
            <div class="doctor-header">
                <h1>Lịch hẹn bệnh nhân</h1>
                <p>Theo dõi toàn bộ lịch đặt khám của bệnh nhân dành cho bác sĩ {{ $doctor->name }}.</p>
            </div>

            <div class="doctor-stats">
                <div class="doctor-stat-card">
                    <h4>Tổng lịch hẹn</h4>
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-note">Toàn bộ lịch đã nhận</div>
                </div>
                <div class="doctor-stat-card">
                    <h4>Lịch hẹn hôm nay</h4>
                    <div class="stat-number">{{ $stats['today'] }}</div>
                    <div class="stat-note">Theo ngày hiện tại</div>
                </div>
                <div class="doctor-stat-card">
                    <h4>Chờ xác nhận</h4>
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div class="stat-note">Cần xử lý</div>
                </div>
                <div class="doctor-stat-card">
                    <h4>Đã xác nhận</h4>
                    <div class="stat-number">{{ $stats['confirmed'] }}</div>
                    <div class="stat-note">Sẵn sàng tiếp nhận</div>
                </div>
            </div>

            <div class="doctor-table-wrap">
                <h3>Danh sách lịch hẹn</h3>

                @if(session('success'))
                    <div class="flash-box success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="flash-box error">{{ session('error') }}</div>
                @endif

                <table class="doctor-table">
                    <thead>
                        <tr>
                            <th>Mã hẹn</th>
                            <th>Bệnh nhân</th>
                            <th>Ngày khám</th>
                            <th>Thứ</th>
                            <th>Giờ</th>
                            <th>Hình thức</th>
                            <th>Địa điểm</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td><strong>{{ $appointment->appointment_code }}</strong></td>
                                <td>
                                    <strong>{{ $appointment->patient->full_name ?? 'Chưa cập nhật' }}</strong>
                                    <div class="muted">{{ $appointment->patient->email ?? 'Không có email' }}</div>
                                    <div class="muted">{{ $appointment->patient->phone ?? 'Không có số điện thoại' }}</div>
                                </td>
                                <td>{{ optional($appointment->appointment_date)->format('d/m/Y') ?? '—' }}</td>
                                <td>{{ $appointment->appointment_day ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td>{{ $appointment->type === 'online' ? 'Online' : 'Trực tiếp' }}</td>
                                <td>{{ $appointment->location ?? '—' }}</td>
                                <td>
                                    <span class="status-badge {{ $appointment->status }}">{{ ucfirst($appointment->status ?? 'pending') }}</span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-stack">
                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('appointments.confirm', $appointment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-confirm">Xác nhận</button>
                                            </form>

                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-cancel">Hủy lịch</button>
                                            </form>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="action-btn btn-disabled">Đã xác nhận</span>
                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-cancel">Hủy lịch</button>
                                            </form>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="action-btn btn-disabled">Đã hủy</span>
                                        @else
                                            <span class="action-btn btn-disabled">Không khả dụng</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">Hiện chưa có lịch hẹn nào từ bệnh nhân.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination-wrap">
                    {{ $appointments->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
