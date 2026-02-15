@extends('layouts.app')

@section('title', 'User Details - ' . ($user['name'] ?? 'Unknown'))

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">User Details</h4>
                <p class="text-muted small mb-0">View and manage hotspot user information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                    class="btn btn-light text-secondary border fw-bold rounded-pill px-3 shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i>Back to Users
                </a>
                <a href="{{ route('mikrotik-suite.netfusion.users.edit', $user['.id']) }}"
                    class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">
                    <i class="bi bi-pencil me-2"></i>Edit User
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- User Info Card -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-gradient-primary text-white p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                                <i class="bi bi-person-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $user['name'] ?? 'Unknown' }}</h5>
                                <p class="mb-0 small opacity-75">Hotspot User Account</p>
                            </div>
                            @if(isset($user['disabled']) && $user['disabled'] == 'true')
                                <span class="badge bg-danger ms-auto">Disabled</span>
                            @else
                                <span class="badge bg-success ms-auto">Active</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3">Account Information</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="text-muted small mb-1">Username</label>
                                <div class="fw-bold text-dark">{{ $user['name'] ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small mb-1">Password</label>
                                <div class="fw-bold font-monospace text-primary">{{ $user['password'] ?? '****' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small mb-1">Profile</label>
                                <div><span class="badge bg-light text-dark border">{{ $user['profile'] ?? 'default' }}</span></div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small mb-1">Server</label>
                                <div class="fw-bold">{{ $user['server'] ?? 'all' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small mb-1">Comment</label>
                                <div class="text-break small">{{ $user['comment'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Stats Card -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h6 class="fw-bold text-dark mb-0">Usage Statistics</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Uptime -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Uptime</span>
                                    <span class="badge bg-light text-dark">{{ $user['uptime'] ?? '0s' }}</span>
                                </div>
                                @if(isset($user['limit-uptime']))
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 100%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Limit: {{ $user['limit-uptime'] }}</small>
                                    </div>
                                @else
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Unlimited</small>
                                @endif
                            </div>

                            <!-- Bytes In -->
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1">Download (Bytes In)</div>
                                    <div class="fw-bold text-info">{{ isset($user['bytes-in']) ? \App\Helpers\Format::bytes($user['bytes-in']) : '0B' }}</div>
                                </div>
                            </div>

                            <!-- Bytes Out -->
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1">Upload (Bytes Out)</div>
                                    <div class="fw-bold text-primary">{{ isset($user['bytes-out']) ? \App\Helpers\Format::bytes($user['bytes-out']) : '0B' }}</div>
                                </div>
                            </div>

                            <!-- Total Bytes -->
                            @if(isset($user['limit-bytes-total']))
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Total Usage</span>
                                        <span class="badge bg-light text-dark">
                                            {{ isset($user['bytes-in']) && isset($user['bytes-out']) ? \App\Helpers\Format::bytes($user['bytes-in'] + $user['bytes-out']) : '0B' }}
                                            / {{ \App\Helpers\Format::bytes($user['limit-bytes-total']) }}
                                        </span>
                                    </div>
                                    <?php
                                    $used = isset($user['bytes-in']) && isset($user['bytes-out']) ? $user['bytes-in'] + $user['bytes-out'] : 0;
                                    $limit = $user['limit-bytes-total'];
                                    $percentage = $limit > 0 ? min(($used / $limit) * 100, 100) : 0;
                                    $barColor = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                                    ?>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $barColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">{{ number_format($percentage, 1) }}% used</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h6 class="fw-bold text-dark mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                @if(isset($user['disabled']) && $user['disabled'] == 'true')
                                    <form action="{{ route('mikrotik-suite.netfusion.users.enable', $user['.id']) }}" method="POST"
                                        onsubmit="return confirm('Enable this user?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 rounded-pill">
                                            <i class="bi bi-toggle-on me-2"></i>Enable User
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('mikrotik-suite.netfusion.users.disable', $user['.id']) }}" method="POST"
                                        onsubmit="return confirm('Disable this user?');">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100 rounded-pill">
                                            <i class="bi bi-toggle-off me-2"></i>Disable User
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('mikrotik-suite.netfusion.users.reset', $user['.id']) }}" method="POST"
                                    onsubmit="return confirm('Reset uptime and bytes counters?');">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-100 rounded-pill">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Counters
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('mikrotik-suite.netfusion.users.edit', $user['.id']) }}"
                                    class="btn btn-info w-100 rounded-pill d-inline-block">
                                    <i class="bi bi-pencil me-2"></i>Edit User
                                </a>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('mikrotik-suite.netfusion.users.destroy', $user['.id']) }}" method="POST"
                                    onsubmit="return confirm('Delete this user permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 rounded-pill">
                                        <i class="bi bi-trash me-2"></i>Delete User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
