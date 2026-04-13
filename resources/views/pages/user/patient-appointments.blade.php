@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom: 20px; font-weight: 800; color: #1d4ed8;">Lịch hẹn của tôi</h2>
    <div style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);overflow:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:900px;">
            <thead>
                <tr style="background:#eff6ff;color:#1d4ed8;">
                    <th style="padding:14px 12px;text-align:left;">Bác sĩ</th>
                    <th style="padding:14px 12px;text-align:left;">Chuyên khoa</th>
                    <th style="padding:14px 12px;text-align:left;">Ngày khám</th>
                    <th style="padding:14px 12px;text-align:left;">Thứ</th>
                    <th style="padding:14px 12px;text-align:left;">Giờ</th>
                    <th style="padding:14px 12px;text-align:left;">Hình thức</th>
                    <th style="padding:14px 12px;text-align:left;">Địa điểm</th>
                    <th style="padding:14px 12px;text-align:left;">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ optional($appointment->doctor->specialty)->name ?? 'N/A' }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ optional($appointment->appointment_date)->format('d/m/Y') ?? 'N/A' }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->appointment_day ?? 'N/A' }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ ucfirst($appointment->type ?? 'N/A') }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->location ?? 'N/A' }}</td>
                        <td style="padding:14px 12px;border-bottom:1px solid #e5e7eb;">{{ ucfirst($appointment->status ?? 'pending') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding:24px;text-align:center;color:#6b7280;">Bạn chưa có lịch hẹn nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
