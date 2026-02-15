@extends('layouts.app')

@section('title', 'Dashboard')

@push('css')
    @push('css')
        @vite(['resources/css/pages/netfusion/dashboard.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/pages/netfusion/dashboard.js'])
    @endpush
@endpush

@section('content')
    <div class="container-fluid">

        @if(!Session::has('router_session'))
            <!-- OFFLINE STATE (Matching Reference) -->
            <div class="offline-placeholder">
                <div class="text-center w-100" style="max-width: 600px;">



                    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                        <div class="card-body p-2">
                            <!-- Connecting Icon -->
                            <div class="router-icon-circle">
                                <i class="bi bi-router"></i>
                            </div>

                            <!-- Main Text -->
                            <h3 class="fw-bold text-dark mb-3">{{ __('netfusion.connect_router') }}</h3>
                            <p class="text-muted mb-4 mx-auto" style="max-width: 420px; line-height: 1.6;">
                                {{ __('netfusion.dashboard_offline') }}
                            </p>

                            <!-- CTA Button -->
                            <div class="mb-4">
                                <a href="{{ route('mikrotik-suite.netfusion.settings.index') }}"
                                    class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm" style="min-width: 250px;">
                                    <i class="bi bi-gear-fill me-2"></i> {{ __('netfusion.configure_connection') }}
                                </a>
                            </div>

                            <hr class="opacity-25 my-4">

                            <!-- Footer Features -->
                            <div class="d-flex flex-wrap justify-content-center gap-3 gap-md-5 mt-4">
                                <div class="feature-item d-flex align-items-center justify-content-center">
                                    <i class="bi bi-shield-check text-success me-2 fs-5"></i> {{ __('netfusion.secure') }}
                                </div>
                                <div class="feature-item d-flex align-items-center justify-content-center">
                                    <i class="bi bi-lightning-charge-fill text-warning me-2 fs-5"></i>
                                    {{ __('netfusion.real_time') }}
                                </div>
                                <div class="feature-item d-flex align-items-center justify-content-center">
                                    <i class="bi bi-code-square text-info me-2 fs-5"></i> {{ __('netfusion.api_based') }}
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Top Right 'Connect Router' Floating Action (Simulated) -->


                </div>
            </div>

        @else
            <!-- ONLINE STATE (Premium Dashboard) -->

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        @if(isset($dashboardData['router']['identity']))
                            {{ $dashboardData['router']['identity'] }}
                        @else
                            {{ __('netfusion.network_overview') }}
                        @endif
                    </h3>
                    <span class="text-muted">{{ __('netfusion.welcome_message') }}</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white px-4 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2">
                        <span class="position-relative d-flex class='p-1'">
                            <span class="spinner-grow spinner-grow-sm text-success status-pulse" role="status"
                                aria-hidden="true"></span>
                        </span>
                        <span class="fw-bold text-success small">{{ __('netfusion.system_online') }}</span>
                    </div>
                    <a href="{{ route('mikrotik-suite.netfusion.settings.index') }}"
                        class="btn btn-white border shadow-sm rounded-circle"
                        style="width: 45px; height: 45px; display: grid; place-items: center;" title="Settings">
                        <i class="bi bi-gear-fill text-muted"></i>
                    </a>
                </div>
            </div>

            <!-- Metric Cards Grid -->
            <div class="row g-4 mb-4">

                <!-- Hotspot Users -->
                <div class="col-12 col-xl-3 col-md-6">
                    <div class="glass-card h-100 p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-secondary text-uppercase fw-semibold"
                                    style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('netfusion.hotspot_active') }}</p>
                                <h2 class="display-stat mb-1" id="active-count-live">
                                    {{ $dashboardData['hotspot']['active_users_count'] ?? 0 }}
                                </h2>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">
                                    <i class="bi bi-arrow-up-short"></i> {{ __('netfusion.live') }}
                                </span>
                            </div>
                            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-wifi"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-12 col-xl-3 col-md-6">
                    <div class="glass-card h-100 p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-secondary text-uppercase fw-semibold"
                                    style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('netfusion.registered_users') }}</p>
                                <h2 class="display-stat mb-1">{{ $dashboardData['hotspot']['total_users_count'] ?? 0 }}</h2>
                                <small class="text-muted">{{ __('netfusion.total_database') }}</small>
                            </div>
                            <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CPU Load -->
                <div class="col-12 col-xl-3 col-md-6">
                    <div class="glass-card h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <p class="text-secondary text-uppercase fw-semibold"
                                    style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('netfusion.cpu_load') }}</p>
                                <h2 class="display-stat mb-0" id="cpu-text">{{ $dashboardData['router']['cpu_load'] ?? 0 }}%
                                </h2>
                            </div>
                            <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px; background-color: #f0f2f5;">
                            <div class="progress-bar bg-warning rounded-pill" id="cpu-bar" role="progressbar"
                                style="width: {{ $dashboardData['router']['cpu_load'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Uptime/Info -->
                <div class="col-12 col-xl-3 col-md-6">
                    <div class="glass-card h-100 p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-secondary text-uppercase fw-semibold"
                                    style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('netfusion.uptime') }}</p>
                                <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;">
                                    {{ $dashboardData['router']['uptime'] ?? '-' }}
                                </h4>
                                <small class="text-muted">{{ $dashboardData['router']['version'] ?? 'RouterOS' }}</small>
                            </div>
                            <div class="stat-icon-wrapper bg-secondary bg-opacity-10 text-secondary">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <!-- Large Traffic Chart -->
                <div class="col-12 col-lg-8">
                    <div class="glass-card h-100">
                        <div
                            class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark">{{ __('netfusion.traffic_overview') }}</h5>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small">{{ __('netfusion.interface') }}:</span>
                                <select id="interface-select"
                                    class="form-select form-select-sm border bg-light fw-bold text-secondary rounded-pill"
                                    style="min-width: 120px; cursor: pointer;">
                                    @if(isset($dashboardData['all_interfaces']))
                                        @foreach($dashboardData['all_interfaces'] as $iface)
                                            <option value="{{ $iface['name'] }}" {{ (isset($dashboardData['traffic']['interface']) && $dashboardData['traffic']['interface'] == $iface['name']) ? 'selected' : '' }}>
                                                {{ $iface['name'] }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="ether1" selected>ether1</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div style="height: 320px; width: 100%;">
                                <canvas id="trafficChart"
                                    data-url="{{ route('mikrotik-suite.netfusion.dashboard.live') }}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity & Quick Actions -->
                <div class="col-12 col-lg-4">
                    <div class="glass-card h-100 d-flex flex-column">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0 text-dark">{{ __('netfusion.quick_actions') }}</h5>
                        </div>
                        <div class="card-body p-4 d-flex flex-column gap-3">

                            <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
                                class="action-card p-3 d-flex align-items-center text-dark">
                                <div class="rounded-pill bg-primary bg-opacity-10 text-primary p-3 me-3">
                                    <i class="bi bi-qr-code fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('netfusion.generate_vouchers') }}</h6>
                                    <small class="text-muted">{{ __('netfusion.batch_user_creation') }}</small>
                                </div>
                            </a>

                            <a href="{{ route('mikrotik-suite.netfusion.users.create') }}"
                                class="action-card p-3 d-flex align-items-center text-dark">
                                <div class="rounded-pill bg-success bg-opacity-10 text-success p-3 me-3">
                                    <i class="bi bi-person-plus-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('netfusion.add_single_user') }}</h6>
                                    <small class="text-muted">{{ __('netfusion.manual_entry') }}</small>
                                </div>
                            </a>

                            <a href="{{ route('mikrotik-suite.netfusion.reports.index') }}"
                                class="action-card p-3 d-flex align-items-center text-dark">
                                <div class="rounded-pill bg-purple bg-opacity-10 text-dark p-3 me-3"
                                    style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1) !important;">
                                    <i class="bi bi-wallet2 fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ __('netfusion.sales_report') }}</h6>
                                    <small class="text-muted">{{ __('netfusion.check_income') }}</small>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Active Table -->
            <div class="glass-card mb-4">
                <div
                    class="card-header bg-transparent border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">{{ __('netfusion.recent_activity') }}</h5>
                    <a href="{{ route('mikrotik-suite.netfusion.active.index') }}"
                        class="btn btn-light text-primary fw-bold text-uppercase small rounded-pill px-4">{{ __('netfusion.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead class="bg-light text-secondary small text-uppercase"
                                style="background-color: #f9fafc !important;">
                                <tr>
                                    <th class="ps-4 border-bottom-0">{{ __('netfusion.user_identity') }}</th>
                                    <th class="border-bottom-0">{{ __('netfusion.ip_address') }}</th>
                                    <th class="border-bottom-0">{{ __('netfusion.uptime') }}</th>
                                    <th class="border-bottom-0">{{ __('netfusion.data_usage') }}</th>
                                    <th class="pe-4 text-end border-bottom-0">{{ __('netfusion.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(array_slice($dashboardData['hotspot']['active_users'] ?? [], 0, 5) as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; color: #8c98a4;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user['user'] }}</div>
                                                    <small class="text-muted">{{ __('netfusion.hotspot_client') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-monospace text-secondary">{{ $user['address'] }}</td>
                                        <td class="text-dark">{{ $user['uptime'] }}</td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span class="text-secondary"><i class="bi bi-arrow-down-short text-success"></i>
                                                    {{ $user['packets-in'] ?? 0 }}</span>
                                                <span class="text-secondary"><i class="bi bi-arrow-up-short text-primary"></i>
                                                    {{ $user['packets-out'] ?? 0 }}</span>
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25">{{ __('netfusion.active') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="bi bi-inbox text-muted fs-1 opacity-25"></i>
                                            </div>
                                            <p class="text-muted mb-0 small">{{ __('netfusion.no_active_sessions') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @endif
    </div>
@endsection