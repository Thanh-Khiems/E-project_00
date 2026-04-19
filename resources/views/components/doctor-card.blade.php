<div class="card doctor-card border-0 shadow-sm text-center py-5 px-4 position-relative mx-auto">
    {{-- Add mx-auto so the card is always centered within its column --}}
    
    <div class="doctor-avatar-wrapper mx-auto mb-4">
        <div class="avatar-container rounded-circle overflow-hidden shadow-sm border border-4 border-white">
            <img src="{{ asset('images/doctors/doctor-1.webp') }}" 
                 class="w-100 h-100 object-fit-cover transition-transform" 
                 alt="{{ $name }}">
        </div>
        <div class="bg-success border border-white border-2 rounded-circle"></div>
    </div>

    <div class="card-body p-0">
        <h3 class="fw-bold text-dark fs-4 mb-1">{{ $name }}</h3>
        <p class="text-primary fw-bold small mb-3 text-uppercase tracking-wider">
            {{ $specialty }}
        </p>
        
        <p class="text-muted small lh-base mb-4 px-2" style="min-height: 60px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
            {{ Str::limit($description, 140) }}
        </p>

        <div class="d-flex flex-column gap-2 mb-4 text-secondary opacity-75" style="font-size: 0.8rem;">
            <div><i class="fas fa-lightbulb text-info me-2"></i>{{ $experience }} Years Experience</div>
            <div><i class="fas fa-hospital text-warning me-2"></i>{{ $hospital }} Dept</div>
        </div>

        @auth
            <button class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm transition-all appointment-btn">
                Book Appointment
            </button>
        @else
            <a href="{{ route('login', ['auth_required' => 1, 'redirect' => route('doctors.index')]) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm transition-all appointment-btn auth-locked" title="Please log in or register to continue">
                Book Appointment
            </a>
        @endauth
    </div>
</div>

<style>
    .doctor-card {
        border-radius: 30px !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        max-width: 300px; /* Force all 8 cards to share the same maximum width */
        height: 100%; /* Make cards equal height within the same row */
    }

    .doctor-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
    }

    .doctor-card:hover .avatar-container img {
        transform: scale(1.15);
    }

    .doctor-avatar-wrapper {
        position: relative;
        width: 130px;
        height: 130px;
    }

    .avatar-container {
        width: 100%;
        height: 100%;
        transition: all 0.4s ease;
    }

    .status-indicator {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 15px;
        height: 15px;
    }

    .appointment-btn:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 15px rgba(13, 110, 253, 0.4);
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }
</style>