@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    {{-- Hero Section --}}
    <div class="card admin-header-card shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1 opacity-75 small">
                            <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.dashboard') }}"
                                    class="text-secondary text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active text-secondary" aria-current="page">Admin Access</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 text-dark">User Management</h4>
                    <p class="mb-0 text-secondary opacity-75">Manage system access, roles, and security permissions.</p>
                </div>
                <div class="d-none d-md-block">
                    <span class="material-icons-outlined text-primary opacity-25" style="font-size: 3.5rem;">manage_accounts</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.users.index') }}" class="admin-stat-card d-block text-decoration-none text-reset">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="stat-label">Total Users</h6>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <span class="material-icons-outlined fs-5">group</span>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="stat-value">{{ number_format($stats['total_users']) }}</h3>
                    <span class="text-secondary small">Registered Accounts</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="admin-stat-card d-block text-decoration-none text-reset">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="stat-label">Active Users</h6>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <span class="material-icons-outlined fs-5">person</span>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="stat-value">{{ number_format($stats['active_users']) }}</h3>
                    <span class="text-success small fw-bold"><i
                            class="material-icons-outlined fs-6 align-middle">verified</i> Online</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="admin-stat-card d-block text-decoration-none text-reset">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="stat-label">Admins</h6>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <span class="material-icons-outlined fs-5">admin_panel_settings</span>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="stat-value">{{ number_format($stats['admins']) }}</h3>
                    <span class="text-danger small fw-bold">Privileged Access</span>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="stat-label">New Today</h6>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <span class="material-icons-outlined fs-5">person_add</span>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="stat-value">{{ number_format($stats['new_users_today']) }}</h3>
                    <span class="text-secondary small">Joined Recently</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Registration Settings --}}
    <div class="card rounded-4 border-0 shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle"
                        style="width: 56px; height: 56px;">
                        <span class="material-icons-outlined fs-4">how_to_reg</span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Public Registration</h5>
                        <p class="mb-0 text-secondary small">
                            @if(\App\Models\SystemSetting::allowRegistration())
                                <span class="text-success fw-bold"><i class='bx bx-check-circle'></i> Open</span>
                                <span class="mx-2">•</span>
                                New users can register accounts
                            @else
                                <span class="text-danger fw-bold"><i class='bx bx-x-circle'></i> Closed</span>
                                <span class="mx-2">•</span>
                                Only admins can create new users
                            @endif
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.settings.toggle-registration') }}" method="POST">
                    @csrf
                    @php
                        $regAction = \App\Models\SystemSetting::allowRegistration() ? 'DISABLE' : 'ENABLE';
                    @endphp
                    <button type="submit"
                        class="btn @if(\App\Models\SystemSetting::allowRegistration()) btn-danger @else btn-success @endif d-flex align-items-center gap-2 px-4 rounded-3 fw-bold"
                        onclick="return confirm('Are you sure you want to {{ $regAction }} public registration?');">
                        <span class="material-icons-outlined fs-5">
                            @if(\App\Models\SystemSetting::allowRegistration())
                                block
                            @else
                                add_circle
                            @endif
                        </span>
                        <span>
                            @if(\App\Models\SystemSetting::allowRegistration())
                                Disable Registration
                            @else
                                Enable Registration
                            @endif
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- User Table --}}
    <div class="admin-table-card shadow mb-4 overflow-hidden d-flex flex-column">
        <div class="card-header px-4 py-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3 w-100 w-md-auto">
                    <h5 class="mb-0 fw-bold text-dark">User List</h5>
                    <button type="button" class="btn btn-primary d-flex align-items-center gap-2 px-4 rounded-3"
                        data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <span class="material-icons-outlined fs-5">add</span>
                        <span>Add User</span>
                    </button>
                </div>

                <form action="{{ route('admin.users.index') }}" method="GET"
                    class="d-flex flex-wrap flex-md-nowrap gap-2 w-100 w-md-auto justify-content-end">
                    <select name="role" class="form-select bg-light border border-secondary border-opacity-25"
                        onchange="this.form.submit()" style="min-width: 120px;">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <select name="status" class="form-select bg-light border border-secondary border-opacity-25"
                        onchange="this.form.submit()" style="min-width: 120px;">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-secondary"><i
                                class="material-icons-outlined fs-5">search</i></span>
                        <input type="text" name="search"
                            class="form-control bg-light border border-secondary border-opacity-25"
                            placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0 flex-grow-1">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Membership</th>
                            <th>Status</th>
                            <th>Security Health</th>
                            <th>Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="last-no-border transition-200">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                            <img src="{{ $user->avatar_url ?? 'https://via.placeholder.com/110x110/212529/fff' }}"
                                                alt="" class="rounded-circle shadow-sm" width="40" height="40"
                                                style="object-fit: cover;">
                                            @if($user->is_active)
                                                <span
                                                    class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                            @else
                                                <span
                                                    class="position-absolute bottom-0 end-0 p-1 bg-secondary border border-light rounded-circle"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                                            <small class="text-secondary opacity-75">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge badge-soft-danger">
                                            <i class='bx bxs-check-shield align-middle me-1'></i> Admin
                                        </span>
                                    @else
                                        <span class="badge badge-soft-info">
                                            <i class='bx bx-user align-middle me-1'></i> User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isTrial = $user->membership_status === 'active' && $user->trial_used_at && !$user->membership_pay_date;
                                        $package = $user->membership_package ?? 'Free';
                                    @endphp

                                    @if($user->isAdmin())
                                        <span class="text-muted small">-</span>
                                    @elseif($isTrial)
                                        <span class="badge bg-warning text-dark border border-warning">
                                            <i class='bx bxs-timer align-middle me-1'></i> Trial
                                        </span>
                                        <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                            Exp: {{ $user->membership_expire ? \Carbon\Carbon::parse($user->membership_expire)->format('d M') : '-' }}
                                        </div>
                                    @elseif($user->membership_status === 'active')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                            <i class='bx bxs-star align-middle me-1'></i> {{ ucfirst($package) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                            {{ ucfirst($package) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge badge-soft-success">Active</span>
                                    @else
                                        <span class="badge badge-soft-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td style="min-width: 150px;">
                                    @php
                                        $score = $user->security_score;
                                        $color = $score > 80 ? 'success' : ($score > 50 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <span class="small text-secondary fw-semibold">Score: {{ $score }}%</span>
                                            <span
                                                class="small text-{{ $color }} fw-bold">{{ $score >= 80 ? 'Strong' : ($score >= 50 ? 'Moderate' : 'Risky') }}</span>
                                        </div>
                                        <div class="progress rounded-pill bg-secondary bg-opacity-10" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $color }}" role="progressbar"
                                                style="--progress-width: {{ $score }}%; width: var(--progress-width);"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-body">{{ $user->created_at->format('d M Y') }}</span>
                                        <small class="text-secondary">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        {{-- Ghost Mode --}}
                                        @if(auth()->id() !== $user->id && !$user->isAdmin())
                                            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-icon" data-bs-toggle="tooltip"
                                                    title="Ghost Mode (Impersonate)">
                                                    <i class='bx bx-ghost fs-5'></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Edit --}}
                                        <button class="btn btn-icon" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                            data-role="{{ $user->role }}"
                                            data-is-active="{{ $user->is_active ? 1 : 0 }}"
                                            data-update-url="{{ route('admin.users.update', $user) }}"
                                            onclick="editUser(this.dataset.id, this.dataset.name, this.dataset.email, this.dataset.role, this.dataset.isActive == 1, this.dataset.updateUrl)"
                                            data-bs-toggle="tooltip" title="Edit User">
                                            <i class='bx bxs-edit fs-5'></i>
                                        </button>

                                        {{-- Delete --}}
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-icon-danger"
                                                    data-bs-toggle="tooltip" title="Delete User">
                                                    <i class='bx bxs-trash fs-5'></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-0 border-0">
                                    <div class="empty-data-state">
                                        <div class="empty-icon">
                                            <i class="material-icons-outlined">person_off</i>
                                        </div>
                                        <h6>No Users Found</h6>
                                        <p>We couldn't find any users matching your search criteria. Try adjusting your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-transparent border-top px-4 py-3">
            {{ $users->links() }}
        </div>
        </div>
    </div>

    {{-- Create User Modal --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 ps-4 pe-4 pt-4">
                    <h5 class="modal-title fw-bold">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Name</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="john@example.com"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Password</label>
                                <input type="password" name="password" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Confirm</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Role</label>
                                <select name="role" class="form-select rounded-3">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Status</label>
                                <select name="status" class="form-select rounded-3">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 ps-4 pe-4 pt-4">
                    <h5 class="modal-title fw-bold">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body px-4">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Name</label>
                            <input type="text" name="name" id="editName" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control rounded-3" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Role</label>
                                <select name="role" id="editRole" class="form-select rounded-3">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-secondary small fw-bold text-uppercase">Status</label>
                                <select name="status" id="editStatus" class="form-select rounded-3">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="border-top my-3"></div>
                        <p class="mb-2 text-muted small"><i
                                class="material-icons-outlined fs-6 align-middle me-1">lock</i>Change Password (Optional)
                        </p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="password" name="password" class="form-control rounded-3"
                                    placeholder="New Password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="password" name="password_confirmation" class="form-control rounded-3"
                                    placeholder="Confirm">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JS for Modal --}}
@endsection