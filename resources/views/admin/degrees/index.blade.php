@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total degrees', 'value' => $stats['total'], 'icon' => 'bi-mortarboard'],
        ['label' => 'Visible', 'value' => $stats['visible'], 'icon' => 'bi-eye'],
        ['label' => 'Hidden', 'value' => $stats['hidden'], 'icon' => 'bi-eye-slash'],
        ['label' => 'Doctors using degree(s)', 'value' => $stats['used'], 'icon' => 'bi-person-badge'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Degree list</h5>
            </div>
        </div>


        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-10"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Search by degree name or description..."></div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Search</button></div>
        </form>

        <div class="row g-4 mt-1">
            @forelse($degrees as $degree)
                <div class="col-md-6 col-xl-4">
                    <div class="info-card h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-soft"><i class="bi bi-mortarboard"></i></div>
                            <span class="status-badge {{ $degree->status }}">{{ $degree->status === 'active' ? 'Visible' : 'Hidden' }}</span>
                        </div>
                        <h5>{{ $degree->name }}</h5>
                        <p>{{ $degree->description ?? 'No description for this degree yet.' }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="empty-box">No matching degree found.</div></div>
            @endforelse
        </div>
    </div>
@endsection
