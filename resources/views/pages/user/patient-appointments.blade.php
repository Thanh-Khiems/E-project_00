@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom: 12px; font-weight: 800; color: #1d4ed8;">My appointments and prescriptions</h2>
    <p style="margin:0 0 20px;color:#6b7280;">Only currently valid appointments within the next 7 days are shown. Completed appointments can be reviewed in your profile.</p>
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
                        <div><strong>Appointment date:</strong> {{ optional($appointment->appointment_date)->format('d/m/Y') ?? 'N/A' }}</div>
                        <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</div>
                        <div><strong>Status:</strong> {{ ucfirst($appointment->status ?? 'pending') }}</div>
                    </div>
                </div>

                @if($appointment->status === 'completed')
                    <div style="margin-top:18px;padding:16px;border-radius:16px;background:#f8fafc;">
                        <div style="margin-bottom:8px;"><strong>Diagnosis:</strong> {{ $appointment->diagnosis ?: 'No diagnosis yet.' }}</div>
                        <div><strong>Doctor's advice:</strong> {{ $appointment->doctor_advice ?: 'No advice yet.' }}</div>
                    </div>

                    <div style="margin-top:18px;padding:18px;border-radius:16px;background:#fff7ed;border:1px solid #fed7aa;">
                        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center;">
                            <div>
                                <h4 style="margin:0 0 6px;color:#9a3412;">Doctor review</h4>
                                <div style="color:#7c2d12;font-size:14px;">After submission, both the doctor and admin can view this review.</div>
                            </div>
                        </div>

                        @if($appointment->review)
                            <div style="margin-top:14px;padding:14px;border-radius:14px;background:#fff;border:1px solid #fdba74;">
                                <div style="font-weight:800;color:#ea580c;font-size:18px;">
                                    {{ str_repeat('★', (int) $appointment->review->rating) }}{{ str_repeat('☆', 5 - (int) $appointment->review->rating) }}
                                </div>
                                <div style="margin-top:8px;"><strong>Rating:</strong> {{ $appointment->review->rating }}/5</div>
                                <div style="margin-top:6px;"><strong>Comment:</strong> {{ $appointment->review->review ?: 'No additional comments.' }}</div>
                                <div style="margin-top:6px;color:#6b7280;font-size:13px;">
                                    Submitted at {{ optional($appointment->review->reviewed_at)->format('d/m/Y H:i') ?? '—' }}
                                </div>
                            </div>
                        @else
                            <form method="POST" action="{{ route('appointments.review', $appointment) }}" style="margin-top:14px;">
                                @csrf
                                <div style="margin-bottom:12px;">
                                    <label for="rating-{{ $appointment->id }}" style="display:block;margin-bottom:6px;font-weight:700;">Rate</label>
                                    <select id="rating-{{ $appointment->id }}" name="rating" style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid #d1d5db;" required>
                                        <option value="">-- Select rating --</option>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }}/5 {{ str_repeat('★', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div style="margin-bottom:12px;">
                                    <label for="review-{{ $appointment->id }}" style="display:block;margin-bottom:6px;font-weight:700;">Comment</label>
                                    <textarea id="review-{{ $appointment->id }}" name="review" rows="4" placeholder="Share your experience with the doctor..." style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid #d1d5db;resize:vertical;"></textarea>
                                </div>

                                <button type="submit" style="padding:12px 16px;border:none;border-radius:12px;background:#ea580c;color:#fff;font-weight:800;cursor:pointer;">
                                    Submit review
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                @if($appointment->prescriptions->count())
                    <div style="margin-top:18px;">
                        <h4 style="margin:0 0 12px;">Prescription</h4>
                        @foreach($appointment->prescriptions as $prescription)
                            <div style="border:1px solid #e5e7eb;border-radius:16px;padding:16px;margin-bottom:12px;">
                                <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                    <div style="font-weight:800;color:#1d4ed8;">{{ $prescription->prescription_code }}</div>
                                    <div style="color:#6b7280;">{{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? '—' }}</div>
                                </div>
                                <div style="margin-top:8px;"><strong>Diagnosis:</strong> {{ $prescription->diagnosis ?: '—' }}</div>
                                <div style="margin-top:6px;"><strong>Notes:</strong> {{ $prescription->notes ?: '—' }}</div>
                                <div style="margin-top:12px;overflow:auto;">
                                    <table style="width:100%;border-collapse:collapse;min-width:760px;">
                                        <thead>
                                            <tr style="background:#eff6ff;">
                                                <th style="padding:10px;text-align:left;">Medication</th>
                                                <th style="padding:10px;text-align:left;">Dosage</th>
                                                <th style="padding:10px;text-align:left;">Frequency</th>
                                                <th style="padding:10px;text-align:left;">Time</th>
                                                <th style="padding:10px;text-align:left;">Quantity</th>
                                                <th style="padding:10px;text-align:left;">Instructions</th>
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
                You do not have any appointments yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
