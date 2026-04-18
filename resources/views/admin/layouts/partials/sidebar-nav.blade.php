<div class="menu-label">System administration</div>
<nav class="nav flex-column sidebar-nav">
    <a class="nav-link {{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}" href="{{ route('admin.doctors.index') }}">
        <i class="bi bi-person-badge"></i><span>Doctor</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.doctors.approvals') ? 'active' : '' }}" href="{{ route('admin.doctors.approvals') }}">
        <i class="bi bi-patch-check"></i><span>Doctor approvals</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}" href="{{ route('admin.specialties.index') }}">
        <i class="bi bi-grid"></i><span>Specialty</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.degrees.*') ? 'active' : '' }}" href="{{ route('admin.degrees.index') }}">
        <i class="bi bi-mortarboard"></i><span>Degree</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}" href="{{ route('admin.patients.index') }}">
        <i class="bi bi-people"></i><span>Patient</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}" href="{{ route('admin.staffs.index') }}">
        <i class="bi bi-person-workspace"></i><span>Staff</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
        <i class="bi bi-calendar-check"></i><span>Appointments</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.medications.*') || request()->routeIs('admin.medicine-types.*') ? 'active' : '' }}" href="{{ route('admin.medications.index') }}">
        <i class="bi bi-capsule"></i><span>Medication catalog</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}" href="{{ route('admin.locations.index') }}">
        <i class="bi bi-geo-alt"></i><span>Locations</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
        <i class="bi bi-journal-richtext"></i><span>Blog</span>
    </a>
</nav>
