@extends('layouts.app')

@section('title')
    {{ __('menu.activity_logs') }}
@endsection

@section('content')
    {{-- Hero Section --}}
    <div class="card admin-header-card shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                     <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1 opacity-75 small">
                            <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.dashboard') }}" class="text-secondary text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active text-secondary" aria-current="page">Monitoring</li>
                        </ol>
                    </nav>
                     <h4 class="mb-1 text-dark">{{ __('menu.activity_logs') }}</h4>
                     <p class="mb-0 text-secondary opacity-75">Monitor user authentication, system events, and security alerts in real-time.</p>
                </div>
                <div class="d-none d-md-block">
                    <span class="material-icons-outlined text-primary opacity-25" style="font-size: 3.5rem;">history_edu</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Overview --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                     <h6 class="stat-label">Total Events</h6>
                     <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                         <span class="material-icons-outlined fs-5">dns</span>
                     </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="stat-value">{{ number_format($totalLogs) }}</h3>
                    <span class="text-success small fw-bold"><i class="material-icons-outlined fs-6 align-middle">trending_up</i> All time</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                     <h6 class="stat-label">Failed Logins</h6>
                     <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                         <span class="material-icons-outlined fs-5">gpp_bad</span>
                     </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                     <h3 class="stat-value">{{ number_format($failedLogins) }}</h3>
                     <span class="text-danger small fw-bold">Alert</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                     <h6 class="stat-label">Unique Users</h6>
                     <div class="stat-icon bg-info bg-opacity-10 text-info">
                         <span class="material-icons-outlined fs-5">group</span>
                     </div>
                </div>
                 <div class="d-flex align-items-baseline gap-2">
                     <h3 class="stat-value">{{ number_format($uniqueUsers) }}</h3>
                     <span class="text-secondary small">Active Accounts</span>
                </div>
            </div>
        </div>
         <div class="col-12 col-md-6 col-xl-3">
            <div class="admin-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                     <h6 class="stat-label">Today's Volume</h6>
                     <div class="stat-icon bg-success bg-opacity-10 text-success">
                         <span class="material-icons-outlined fs-5">today</span>
                     </div>
                </div>
                 <div class="d-flex align-items-baseline gap-2">
                     <h3 class="stat-value">{{ number_format($todayLogs) }}</h3>
                     <span class="text-secondary small">Records generated</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Volume History Chart --}}
    <div class="admin-table-card shadow mb-4 overflow-hidden d-flex flex-column">
        <div class="card-header px-4 py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark">Log Volume (Last 7 Days)</h5>
        </div>
        <div class="card-body px-4 py-4">
            <div id="chart1" 
                 data-chart-data="{{ json_encode($chartValues) }}" 
                 data-chart-labels="{{ json_encode($chartLabels) }}">
            </div>
        </div>
    </div>

    {{-- Activity Logs Table with Filters --}}
    <div class="admin-table-card shadow overflow-hidden d-flex flex-column">
        {{-- Filters Header --}}
        <div class="card-header px-4 py-3">
            <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-12 col-md-3">
                     <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-secondary"><i class="material-icons-outlined fs-5">search</i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search logs..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="action" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="">All Events</option>
                        <option value="LOGIN" {{ request('action') == 'LOGIN' ? 'selected' : '' }}>Login Success</option>
                        <option value="LOGIN_FAILED" {{ request('action') == 'LOGIN_FAILED' ? 'selected' : '' }}>Login Failed</option>
                        <option value="LOGOUT" {{ request('action') == 'LOGOUT' ? 'selected' : '' }}>Logout</option>
                    </select>
                </div>
                 <div class="col-6 col-md-2">
                    <input type="date" name="start_date" class="form-control bg-light" value="{{ request('start_date') }}" placeholder="Start Date" title="Start Date">
                </div>
                 <div class="col-6 col-md-2">
                    <input type="date" name="end_date" class="form-control bg-light" value="{{ request('end_date') }}" placeholder="End Date" title="End Date">
                </div>
                <div class="col-12 col-md-3 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-3 rounded-3" title="Apply Filters">
                         <i class="material-icons-outlined align-middle">filter_alt</i>
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary rounded-3" data-bs-toggle="tooltip" title="Reset Filters">
                        <i class="material-icons-outlined align-middle">restart_alt</i>
                    </a>
                    <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn btn-success text-white rounded-3" data-bs-toggle="tooltip" title="Export CSV">
                         <i class="material-icons-outlined align-middle">download</i>
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body p-0 flex-grow-1">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>IP Address</th>
                            <th>Date</th>
                            <th class="text-end">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="last-no-border transition-200">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                             <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 40px; height: 40px;">
                                                 <span class="material-icons-outlined fs-5">person</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">
                                                {{ $log->user ? $log->user->name : 'System/Guest' }}
                                            </h6>
                                            <small class="text-secondary opacity-75">
                                                {{ $log->user ? $log->user->email : 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($log->action == 'LOGIN')
                                             <span class="material-icons-outlined text-success fs-5">login</span>
                                             <span class="fw-semibold text-dark">Login</span>
                                        @elseif ($log->action == 'LOGOUT')
                                             <span class="material-icons-outlined text-secondary fs-5">logout</span>
                                             <span class="fw-semibold text-dark">Logout</span>
                                        @elseif ($log->action == 'LOGIN_FAILED')
                                             <span class="material-icons-outlined text-danger fs-5">gpp_bad</span>
                                             <span class="fw-semibold text-danger">Failed Login</span>
                                        @else
                                             <span class="material-icons-outlined text-info fs-5">info</span>
                                             <span class="fw-semibold text-dark">{{ ucfirst(strtolower($log->action)) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($log->action == 'LOGIN')
                                        <span class="badge badge-soft-success">Successful</span>
                                    @elseif ($log->action == 'LOGOUT')
                                        <span class="badge badge-soft-secondary">Logged Out</span>
                                    @elseif ($log->action == 'LOGIN_FAILED')
                                        <span class="badge badge-soft-danger">Failed</span>
                                    @else
                                         <span class="badge badge-soft-info">Info</span>
                                    @endif
                                </td>
                                <td>
                                     <div class="d-flex align-items-center gap-2 text-secondary">
                                        <span class="material-icons-outlined fs-6">dns</span>
                                        <span>{{ $log->ip_address }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark">{{ $log->created_at->format('d M Y, H:i') }}</span>
                                        <small class="text-secondary">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light border rounded-3 text-secondary" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $log->user_agent }}">
                                        <span class="material-icons-outlined fs-6 align-middle me-1">devices</span>
                                        Device
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-0 border-0">
                                    <div class="empty-data-state">
                                        <div class="empty-icon">
                                            <i class="material-icons-outlined">history_edu</i>
                                        </div>
                                        <h6>No Activity Logs</h6>
                                        <p>There are no system events or user activities recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-transparent border-top px-4 py-3">
            {{ $logs->links() }}
        </div>
        </div>
    </div>

@endsection

@push('css')
    
@endpush

@push('scripts')
    <script src="{{ URL::asset('build/plugins/apexchart/apexcharts.min.js') }}"></script>
    @vite(['resources/js/pages/admin/activity-logs.js'])
@endpush
