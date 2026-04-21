@extends('layouts.app')

@section('content')
<div style="background:#f5f7fb;min-height:100vh;padding:24px;">
    <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:260px 1fr;gap:24px;">
        <aside style="background:#fff;border-radius:20px;padding:24px 18px;box-shadow:0 10px 26px rgba(0,0,0,.05);display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <h2 style="color:#1d4ed8;margin:0 0 8px;font-size:22px;font-weight:800;">Doctor Panel</h2>
                <p style="color:#6b7280;font-size:14px;margin-bottom:24px;">Manage appointments, complete visits, issue prescriptions, and view patient reviews.</p>

                <div style="display:flex;flex-direction:column;gap:10px;">
                    <a href="{{ route('doctor.manage') }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;">Dashboard</a>
                    <a href="{{ route('doctor.manage', ['tab' => 'schedule']) }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;color:#374151;font-weight:600;">Schedule Settings</a>
                    <a href="{{ route('doctor.appointments') }}" style="display:block;padding:12px 14px;border-radius:12px;text-decoration:none;background:#eff6ff;color:#1d4ed8;font-weight:700;">Appointments</a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;padding:12px 14px;border:none;border-radius:12px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Log out</button>
            </form>
        </aside>

        <main>
            <div style="display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:16px;margin-bottom:20px;">
                @foreach([
                    ['Next 7 days', $stats['total'], '#1d4ed8'],
                    ['Remaining today', $stats['today'], '#0f766e'],
                    ['Pending confirmation', $stats['pending'], '#d97706'],
                    ['Confirmed', $stats['confirmed'], '#2563eb'],
                    ['Completed', $stats['completed'], '#059669'],
                    ['Reviews', $stats['reviews_count'], '#ea580c'],
                ] as $card)
                    <div style="background:#fff;border-radius:18px;padding:18px;box-shadow:0 10px 26px rgba(0,0,0,.05);">
                        <div style="font-size:13px;color:#6b7280;">{{ $card[0] }}</div>
                        <div style="font-size:28px;font-weight:800;color:{{ $card[2] }};margin-top:6px;">{{ $card[1] }}</div>
                    </div>
                @endforeach
            </div>

            <div style="background:#fff;border-radius:20px;padding:22px;box-shadow:0 10px 26px rgba(0,0,0,.05);margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;align-items:center;">
                    <div>
                        <div style="font-size:14px;color:#6b7280;">Average rating</div>
                        <div style="font-size:32px;font-weight:800;color:#ea580c;">{{ number_format($stats['average_rating'], 1) }}/5</div>
                    </div>
                    <div style="color:#6b7280;font-size:14px;">Total reviews: <strong>{{ $stats['reviews_count'] }}</strong></div>
                </div>
            </div>

            <div style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 10px 26px rgba(0,0,0,.05);overflow:auto;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px;">
                    <div>
                        <h3 style="margin:0;color:#111827;">Appointment list</h3>
                        <p style="margin:6px 0 0;color:#6b7280;">Only currently valid appointments within the next 7 days are shown so doctors can track them more easily.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:#ecfdf5;color:#065f46;">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div style="margin-bottom:16px;padding:12px 16px;border-radius:12px;background:#fef2f2;color:#991b1b;">{{ session('error') }}</div>
                @endif

                <table style="width:100%;border-collapse:collapse;min-width:1280px;">
                    <thead>
                        <tr style="background:#eff6ff;color:#1d4ed8;">
                            <th style="padding:12px;text-align:left;">Appointment code</th>
                            <th style="padding:12px;text-align:left;">Patient</th>
                            <th style="padding:12px;text-align:left;">Appointment date</th>
                            <th style="padding:12px;text-align:left;">Time</th>
                            <th style="padding:12px;text-align:left;">Type</th>
                            <th style="padding:12px;text-align:left;">Status</th>
                            <th style="padding:12px;text-align:left;">Diagnosis</th>
                            <th style="padding:12px;text-align:left;">Prescription</th>
                            <th style="padding:12px;text-align:left;">Review</th>
                            <th style="padding:12px;text-align:left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;"><strong>{{ $appointment->appointment_code }}</strong></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    <strong>{{ $appointment->patient->full_name ?? 'Not updated' }}</strong>
                                    <div style="color:#6b7280;font-size:13px;">{{ $appointment->patient->email ?? 'No email' }}</div>
                                    <div style="color:#6b7280;font-size:13px;">{{ $appointment->patient->phone ?? 'No phone number' }}</div>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    {{ optional($appointment->appointment_date)->format('d/m/Y') ?? '—' }}<br>
                                    <span style="font-size:12px;color:#6b7280;">{{ $appointment->appointment_day ?? '—' }}</span>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">{{ $appointment->type === 'online' ? 'Online' : 'In-person' }}<div style="font-size:12px;color:#6b7280;">{{ $appointment->location ?? '—' }}</div></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    @php
                                        $statusColors = ['pending' => '#d97706', 'confirmed' => '#2563eb', 'completed' => '#059669', 'cancelled' => '#dc2626'];
                                    @endphp
                                    <span style="display:inline-block;padding:6px 10px;border-radius:999px;background:{{ ($statusColors[$appointment->status] ?? '#6b7280') }}15;color:{{ $statusColors[$appointment->status] ?? '#6b7280' }};font-weight:700;">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;max-width:220px;">
                                    {{ $appointment->diagnosis ? \Illuminate\Support\Str::limit($appointment->diagnosis, 90) : 'No diagnosis yet' }}
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    {{ $appointment->prescriptions->count() }} prescription
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;max-width:240px;">
                                    @if($appointment->review)
                                        <div style="font-weight:800;color:#ea580c;">{{ str_repeat('★', (int) $appointment->review->rating) }}{{ str_repeat('☆', 5 - (int) $appointment->review->rating) }}</div>
                                        <div style="font-size:13px;color:#111827;margin-top:4px;">{{ $appointment->review->rating }}/5 · {{ $appointment->review->patient->full_name ?? 'Patient' }}</div>
                                        <div style="font-size:12px;color:#6b7280;margin-top:4px;">{{ \Illuminate\Support\Str::limit($appointment->review->review ?: 'No comment.', 70) }}</div>
                                    @else
                                        <span style="color:#6b7280;font-size:13px;">No reviews yet</span>
                                    @endif
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb;">
                                    <div style="display:flex;flex-direction:column;gap:8px;min-width:170px;">
                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('appointments.confirm', $appointment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;">Accept appointment</button>
                                            </form>
                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Are you sure you want to decline this appointment?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Decline</button>
                                            </form>
                                        @elseif($appointment->status === 'confirmed')
                                            <a href="{{ route('doctor.appointments.prescriptions.create', $appointment) }}" style="display:block;text-align:center;padding:10px 12px;border-radius:10px;background:#059669;color:#fff;text-decoration:none;font-weight:700;">Complete visit & prescribe</a>
                                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="width:100%;padding:10px 12px;border:none;border-radius:10px;background:#fee2e2;color:#b91c1c;font-weight:700;cursor:pointer;">Cancel appointment</button>
                                            </form>
                                        @elseif($appointment->status === 'completed')
                                            <a href="{{ route('doctor.appointments.prescriptions.create', $appointment) }}" style="display:block;text-align:center;padding:10px 12px;border-radius:10px;background:#eff6ff;color:#1d4ed8;text-decoration:none;font-weight:700;">View / add prescription</a>
                                        @else
                                            <span style="padding:10px 12px;border-radius:10px;background:#f3f4f6;color:#6b7280;font-weight:700;text-align:center;">Cancelled</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="padding:24px;text-align:center;color:#6b7280;">There are no appointments yet.</td>
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
