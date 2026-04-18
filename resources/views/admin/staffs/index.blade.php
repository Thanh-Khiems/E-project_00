@extends('admin.layouts.app')

@section('content')
    @include('admin.layouts.partials.stats', ['items' => [
        ['label' => 'Total staff', 'value' => $stats['total'], 'icon' => 'bi-person-workspace'],
        ['label' => 'Working', 'value' => $stats['working'], 'icon' => 'bi-briefcase'],
        ['label' => 'On leave', 'value' => $stats['leave'], 'icon' => 'bi-airplane'],
        ['label' => 'Administrator', 'value' => $stats['admin'], 'icon' => 'bi-shield-lock'],
    ]])

    <div class="panel-card mb-4">
        <div class="panel-head">
            <div>
                <h5>Staff list</h5>
                <p>Manage receptionists, nurses, accountants, technicians, and administrators.</p>
            </div>
            <button class="btn btn-primary">+ Add staff</button>
        </div>

        <form method="GET" class="row g-3 filter-bar">
            <div class="col-md-5"><input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Name, email, phone number, department..."></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="working" @selected(request('status') == 'working')>Working</option>
                    <option value="leave" @selected(request('status') == 'leave')>On leave</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-outline-primary">Filter</button></div>
            <div class="col-md-2 d-grid"><button type="button" class="btn btn-light">Permissions</button></div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th>Staff</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $staff)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-circle">{{ strtoupper(substr($staff->name, 0, 1)) }}</div>
                                    <div>
                                        <strong>{{ $staff->name }}</strong>
                                        <small>{{ $staff->email }} · {{ $staff->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ ucfirst($staff->role ?? 'staff') }}</td>
                            <td>{{ $staff->department ?? '—' }}</td>
                            <td>{{ $staff->shift ?? 'Office hours' }}</td>
                            <td><span class="status-badge {{ $staff->status }}">{{ $staff->status }}</span></td>
                            <td class="text-end table-actions">
                                <a href="#">Details</a>
                                <a href="#">Update</a>
                                <a href="#" class="text-danger">Disable</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No staff data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $staffs->links() }}
    </div>
@endsection
