@extends('layouts.app')

@section('content')
<div style="max-width:1200px;margin:32px auto;padding:0 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;">
        <div>
            <h2 style="margin:0;color:#1d4ed8;">Hoàn tất khám & kê đơn</h2>
            <p style="margin:8px 0 0;color:#6b7280;">Lịch hẹn {{ $appointment->appointment_code }} · {{ optional($appointment->appointment_date)->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('doctor.appointments') }}" style="padding:10px 14px;border-radius:10px;background:#eff6ff;color:#1d4ed8;text-decoration:none;font-weight:700;">Quay lại Appointments</a>
    </div>

    @if($errors->any())
        <div style="margin-bottom:16px;padding:14px 16px;border-radius:12px;background:#fef2f2;color:#991b1b;">
            <ul style="margin:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:grid;grid-template-columns:340px 1fr;gap:24px;">
        <div style="background:#fff;border-radius:18px;padding:20px;box-shadow:0 10px 26px rgba(0,0,0,.05);height:fit-content;">
            <h4 style="margin-top:0;">Thông tin cuộc hẹn</h4>
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:8px 0;color:#6b7280;">Bệnh nhân</td><td style="padding:8px 0;font-weight:700;">{{ $appointment->patient->full_name ?? '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Email</td><td style="padding:8px 0;">{{ $appointment->patient->email ?? '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Điện thoại</td><td style="padding:8px 0;">{{ $appointment->patient->phone ?? '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Bác sĩ</td><td style="padding:8px 0;">{{ $appointment->doctor->name ?? '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Chuyên khoa</td><td style="padding:8px 0;">{{ optional($appointment->doctor->specialty)->name ?? '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Khung giờ</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td></tr>
                <tr><td style="padding:8px 0;color:#6b7280;">Trạng thái</td><td style="padding:8px 0;font-weight:700;">{{ ucfirst($appointment->status) }}</td></tr>
            </table>

            @if($appointment->prescriptions->count())
                <hr style="margin:18px 0;">
                <h4 style="margin-top:0;">Các toa thuốc đã cấp</h4>
                @foreach($appointment->prescriptions as $prescription)
                    <div style="padding:12px;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:12px;">
                        <div style="font-weight:800;color:#1d4ed8;">{{ $prescription->prescription_code }}</div>
                        <div style="font-size:13px;color:#6b7280;">{{ optional($prescription->issued_at)->format('d/m/Y H:i') }}</div>
                        <div style="margin-top:8px;"><strong>Chẩn đoán:</strong> {{ $prescription->diagnosis ?: '—' }}</div>
                        <div style="margin-top:8px;"><strong>Số thuốc:</strong> {{ $prescription->items->count() }}</div>
                    </div>
                @endforeach
            @endif
        </div>

        <div style="background:#fff;border-radius:18px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);">
            <form method="POST" action="{{ route('doctor.appointments.prescriptions.store', $appointment) }}">
                @csrf
                <div style="display:grid;grid-template-columns:1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-weight:700;margin-bottom:8px;">Chẩn đoán của bác sĩ</label>
                        <textarea name="diagnosis" rows="4" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                    </div>
                    <div>
                        <label style="display:block;font-weight:700;margin-bottom:8px;">Lời dặn cho bệnh nhân</label>
                        <textarea name="doctor_advice" rows="3" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">{{ old('doctor_advice', $appointment->doctor_advice) }}</textarea>
                    </div>
                    <div>
                        <label style="display:block;font-weight:700;margin-bottom:8px;">Ghi chú toa thuốc</label>
                        <textarea name="prescription_notes" rows="3" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">{{ old('prescription_notes') }}</textarea>
                    </div>
                </div>

                <div style="margin-top:22px;">
                    <h3 style="margin:0 0 12px;">Danh sách thuốc</h3>
                    <p style="margin:0 0 16px;color:#6b7280;">Mỗi lần bấm lưu sẽ tạo thêm một toa thuốc mới cho chính cuộc hẹn này.</p>

                    @php $oldItems = old('items', [['medication_id' => '', 'dosage' => '', 'frequency' => '', 'duration' => '', 'quantity' => 1, 'instructions' => '', 'notes' => '']]); @endphp

                    <div id="prescription-items" style="display:flex;flex-direction:column;gap:16px;">
                        @foreach($oldItems as $index => $item)
                            <div class="prescription-item" style="border:1px solid #e5e7eb;border-radius:16px;padding:18px;background:#f9fafb;">
                                <div style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr 0.8fr;gap:12px;">
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Thuốc</label>
                                        <select name="items[{{ $index }}][medication_id]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">
                                            <option value="">-- Chọn thuốc --</option>
                                            @foreach($medications as $medication)
                                                <option value="{{ $medication->id }}" {{ (string) ($item['medication_id'] ?? '') === (string) $medication->id ? 'selected' : '' }}>
                                                    {{ $medication->name }} - {{ $medication->dosage }}{{ $medication->medicineType ? ' · ' . $medication->medicineType->name : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Liều dùng</label>
                                        <input type="text" name="items[{{ $index }}][dosage]" value="{{ $item['dosage'] ?? '' }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="1 viên">
                                    </div>
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Tần suất</label>
                                        <input type="text" name="items[{{ $index }}][frequency]" value="{{ $item['frequency'] ?? '' }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="2 lần/ngày">
                                    </div>
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Thời gian dùng</label>
                                        <input type="text" name="items[{{ $index }}][duration]" value="{{ $item['duration'] ?? '' }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="5 ngày">
                                    </div>
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Số lượng</label>
                                        <input type="number" min="1" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">
                                    </div>
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;margin-top:12px;">
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Hướng dẫn dùng thuốc</label>
                                        <input type="text" name="items[{{ $index }}][instructions]" value="{{ $item['instructions'] ?? '' }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="Uống sau ăn">
                                    </div>
                                    <div>
                                        <label style="display:block;font-weight:700;margin-bottom:8px;">Ghi chú</label>
                                        <input type="text" name="items[{{ $index }}][notes]" value="{{ $item['notes'] ?? '' }}" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="Theo dõi phản ứng phụ">
                                    </div>
                                    <div style="display:flex;align-items:end;">
                                        <button type="button" onclick="this.closest('.prescription-item').remove()" style="padding:12px 14px;border:none;border-radius:12px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Xóa dòng</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-item" style="margin-top:14px;padding:10px 14px;border:none;border-radius:12px;background:#eff6ff;color:#1d4ed8;font-weight:700;cursor:pointer;">+ Thêm thuốc</button>
                </div>

                <div style="margin-top:24px;display:flex;gap:12px;">
                    <button type="submit" style="padding:12px 18px;border:none;border-radius:12px;background:#059669;color:#fff;font-weight:800;cursor:pointer;">Hoàn tất khám và phát hành toa thuốc</button>
                    <a href="{{ route('doctor.appointments') }}" style="padding:12px 18px;border-radius:12px;background:#f3f4f6;color:#374151;text-decoration:none;font-weight:700;">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const medications = @json($medications->map(fn($med) => [
        'id' => $med->id,
        'label' => $med->name . ' - ' . $med->dosage . ($med->medicineType ? ' · ' . $med->medicineType->name : ''),
    ]));
    let itemIndex = {{ count($oldItems) }};

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('prescription-items');
        const options = medications.map(med => `<option value="${med.id}">${med.label}</option>`).join('');
        const wrapper = document.createElement('div');
        wrapper.className = 'prescription-item';
        wrapper.style.cssText = 'border:1px solid #e5e7eb;border-radius:16px;padding:18px;background:#f9fafb;';
        wrapper.innerHTML = `
            <div style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr 0.8fr;gap:12px;">
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Thuốc</label>
                    <select name="items[${itemIndex}][medication_id]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">
                        <option value="">-- Chọn thuốc --</option>
                        ${options}
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Liều dùng</label>
                    <input type="text" name="items[${itemIndex}][dosage]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="1 viên">
                </div>
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Tần suất</label>
                    <input type="text" name="items[${itemIndex}][frequency]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="2 lần/ngày">
                </div>
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Thời gian dùng</label>
                    <input type="text" name="items[${itemIndex}][duration]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="5 ngày">
                </div>
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Số lượng</label>
                    <input type="number" min="1" name="items[${itemIndex}][quantity]" value="1" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;margin-top:12px;">
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Hướng dẫn dùng thuốc</label>
                    <input type="text" name="items[${itemIndex}][instructions]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="Uống sau ăn">
                </div>
                <div>
                    <label style="display:block;font-weight:700;margin-bottom:8px;">Ghi chú</label>
                    <input type="text" name="items[${itemIndex}][notes]" style="width:100%;padding:12px;border:1px solid #d1d5db;border-radius:12px;" placeholder="Theo dõi phản ứng phụ">
                </div>
                <div style="display:flex;align-items:end;">
                    <button type="button" onclick="this.closest('.prescription-item').remove()" style="padding:12px 14px;border:none;border-radius:12px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Xóa dòng</button>
                </div>
            </div>
        `;
        container.appendChild(wrapper);
        itemIndex++;
    });
</script>
@endsection
