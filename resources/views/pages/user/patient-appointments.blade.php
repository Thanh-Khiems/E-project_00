@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom: 20px; font-weight: 800; color: #1d4ed8;">Lịch hẹn và toa thuốc của tôi</h2>
    <div style="display:flex;flex-direction:column;gap:20px;">
        @forelse($appointments as $appointment)
            <div style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);">
                <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                    <div>
                        <div style="font-weight:800;color:#1d4ed8;">{{ $appointment->appointment_code }}</div>
                        <div style="margin-top:8px;font-size:18px;font-weight:700;">{{ $appointment->doctor->name ?? 'N/A' }}</div>
                        <div style="color:#6b7280;">{{ optional($appointment->doctor->specialty)->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div><strong>Ngày khám:</strong> {{ optional($appointment->appointment_date)->format('d/m/Y') ?? 'N/A' }}</div>
                        <div><strong>Giờ:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
                        <div><strong>Trạng thái:</strong> {{ ucfirst($appointment->status ?? 'pending') }}</div>
                    </div>
                </div>

                @if($appointment->status === 'completed')
                    <div style="margin-top:18px;padding:16px;border-radius:16px;background:#f8fafc;">
                        <div style="margin-bottom:8px;"><strong>Chẩn đoán:</strong> {{ $appointment->diagnosis ?: 'Chưa có chẩn đoán.' }}</div>
                        <div><strong>Lời dặn của bác sĩ:</strong> {{ $appointment->doctor_advice ?: 'Chưa có lời dặn.' }}</div>
                    </div>
                @endif

                @if($appointment->prescriptions->count())
                    <div style="margin-top:18px;">
                        <h4 style="margin:0 0 12px;">Toa thuốc</h4>
                        @foreach($appointment->prescriptions as $prescription)
                            <div style="border:1px solid #e5e7eb;border-radius:16px;padding:16px;margin-bottom:12px;">
                                <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                    <div style="font-weight:800;color:#1d4ed8;">{{ $prescription->prescription_code }}</div>
                                    <div style="color:#6b7280;">{{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? '—' }}</div>
                                </div>
                                <div style="margin-top:8px;"><strong>Chẩn đoán:</strong> {{ $prescription->diagnosis ?: '—' }}</div>
                                <div style="margin-top:6px;"><strong>Ghi chú:</strong> {{ $prescription->notes ?: '—' }}</div>
                                <div style="margin-top:12px;overflow:auto;">
                                    <table style="width:100%;border-collapse:collapse;min-width:760px;">
                                        <thead>
                                            <tr style="background:#eff6ff;">
                                                <th style="padding:10px;text-align:left;">Thuốc</th>
                                                <th style="padding:10px;text-align:left;">Liều dùng</th>
                                                <th style="padding:10px;text-align:left;">Tần suất</th>
                                                <th style="padding:10px;text-align:left;">Thời gian</th>
                                                <th style="padding:10px;text-align:left;">Số lượng</th>
                                                <th style="padding:10px;text-align:left;">Hướng dẫn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($prescription->items as $item)
                                                <tr>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->medication->name ?? '—' }}<div style="font-size:12px;color:#6b7280;">{{ $item->medication->dosage ?? '' }}</div></td>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->dosage }}</td>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->frequency }}</td>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->duration }}</td>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->quantity }}</td>
                                                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item->instructions ?: '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);text-align:center;color:#6b7280;">
                Bạn chưa có lịch hẹn nào.
            </div>
        @endforelse
    </div>
</div>
@endsection
