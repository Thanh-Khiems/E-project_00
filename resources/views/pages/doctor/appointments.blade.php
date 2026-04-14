@extends('layouts.app')

@section('content')
<div style="background:#f5f7fb;min-height:100vh;padding:24px;">
    <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:260px 1fr;gap:24px;">
        <aside style="background:#fff;border-radius:20px;padding:24px 18px;box-shadow:0 10px 26px rgba(0,0,0,.05);display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <h2 style="color:#1d4ed8;margin:0 0 8px;font-size:22px;font-weight:800;">Doctor Panel</h2>
                <p style="color:#6b7280;font-size:14px;margin-bottom:24px;">Quản lý lịch hẹn, hoàn tất buổi khám và phát hành đơn thuốc.</p>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('doctor.dashboard') }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;">Dashboard</a>
                    <a href="{{ route('doctor.manage', ['tab' => 'schedule']) }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;">Schedule Settings</a>
                    <a href="{{ route('doctor.appointments') }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;background:#eff6ff;color:#1d4ed8;font-weight:700;">Appointments</a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;padding:12px 14px;border:none;border-radius:12px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Đăng xuất</button>
            </form>
        </aside>

        <main>
            <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:16px;margin-bottom:20px;">
                @foreach([
                    ['Tổng lịch hẹn', $stats['total'], '#1d4ed8'],
                    ['Hôm nay', $stats['today'], '#0f766e'],
                    ['Chờ xác nhận', $stats['pending'], '#d97706'],
                    ['Đã xác nhận', $stats['confirmed'], '#2563eb'],
                    ['Hoàn tất', $stats['completed'], '#059669'],
                ] as $card)
                    <div style="background:#fff;border-radius:18px;padding:18px;box-shadow:0 10px 26px rgba(0,0,0,.05);">
                        <div style="font-size:13px;color:#6b7280;">{{ $card[0] }}</div>
                        <div style="font-size:28px;font-weight:800;color:{{ $card[2] }};margin-top:6px;">{{ $card[1] }}</div>
                    </div>
                @endforeach
            </div>

            <div style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);overflow:auto;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;">
                    <div>
                        <h3 style="margin:0;color:#111827;">Danh sách lịch hẹn</h3>
                        <p style="margin:6px 0 0;color:#6b7280;">Bác sĩ xác nhận lịch, từ chối lịch hoặc hoàn tất buổi khám để phát hành toa thuốc.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:#ecfdf5;color:#065f46;">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:#fef2f2;color:#991b1b;">{{ session('error') }}</div>
                @endif

                <table style="width:100%;border-collapse:collapse;min-width:1100px;">
                    <thead>
                        <tr style="background:#eff6ff;color:#1d4ed8;">
                            <th style="padding:12px;text-align:left;">Mã hẹn</th>
                            <th style="padding:12px;text-align:left;">Bệnh nhân</th>
                            <th style="padding:12px;text-align:left;">Ngày khám</th>
                            <th style="padding:12px;text-align:left;">Giờ</th>
                            <th style="padding:12px;text-align:left;">Hình thức</th>
                            <th style="padding:12px;text-align:left;">Trạng thái</th>
                            <th style="padding:12px;text-align:left;">Chẩn đoán</th>
                            <th style="padding:12px;text-align:left;">Toa thuốc</th>
                            <th style="padding:12px;text-align:left;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;"><strong>{{ $appointment->appointment_code }}</strong></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    <strong>{{ $appointment->patient->full_name ?? 'Chưa cập nhật' }}</strong>
                                    <div style="color:#6b7280;font-size:13px;">{{ $appointment->patient->email ?? 'Không có email' }}</div>
                                    <div style="color:#6b7280;font-size:13px;">{{ $appointment->patient->phone ?? 'Không có SĐT' }}</div>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    {{ optional($appointment->appointment_date)->format('d/m/Y') ?? '—' }}<br>
                                    <span style="font-size:12px;color:#6b7280;">{{ $appointment->appointment_day ?? '—' }}</span>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->type === 'online' ? 'Online' : 'Trực tiếp' }}<div style="font-size:12px;color:#6b7280;">{{ $appointment->location ?? '—' }}</div></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    @php
                                        $statusColors = ['pending' => '#d97706', 'confirmed' => '#2563eb', 'completed' => '#059669', 'cancelled' => '#dc2626'];
                                    @endphp
                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;background:{{ ($statusColors[$appointment->status] ?? '#6b7280') }}15;color:{{ $statusColors[$appointment->status] ?? '#6b7280' }};font-weight:700;">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;max-width:220px;">
                                    {{ $appointment->diagnosis ? \Illuminate\Support\Str::limit($appointment->diagnosis, 90) : 'Chưa có chẩn đoán' }}
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    {{ $appointment->prescriptions->count() }} toa
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    <div style="display:flex;flex-direction:column;gap:8px;min-width:170px;">
                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('appointments.confirm', $appointment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;">Nhận lịch hẹn</button>
                                            </form>
                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Bạn chắc chắn muốn từ chối lịch hẹn này?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Từ chối</button>
                                            </form>
                                        @elseif($appointment->status === 'confirmed')
                                            <a href="{{ route('doctor.appointments.prescriptions.create', $appointment) }}" style="display:block;text-align:center;padding:10px 12px;border-radius:10px;background:#059669;color:#fff;text-decoration:none;font-weight:700;">Hoàn tất khám & kê đơn</a>
                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Hủy lịch</button>
                                            </form>
                                        @elseif($appointment->status === 'completed')
                                            <a href="{{ route('doctor.appointments.prescriptions.create', $appointment) }}" style="display:block;text-align:center;padding:10px 12px;border-radius:10px;background:#eff6ff;color:#1d4ed8;text-decoration:none;font-weight:700;">Xem / thêm đơn thuốc</a>
                                        @else
                                            <span style="padding:10px 12px;border-radius:10px;background:#f3f4f6;color:#6b7280;font-weight:700;text-align:center;">Đã hủy</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding:24px;text-align:center;color:#6b7280;">Chưa có lịch hẹn nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div style="margin-top:18px;">
                    {{ $appointments->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
