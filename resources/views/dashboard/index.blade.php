@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-6);
        margin-bottom: var(--spacing-8);
    }

    .chart-card {
        grid-column: span 3;
        min-height: 400px;
    }

    .activity-card {
        grid-column: span 1;
        min-height: 400px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-card {
            grid-column: span 2;
        }

        .activity-card {
            grid-column: span 2;
        }
    }

    @media (max-width: 640px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .chart-card {
            grid-column: span 1;
        }

        .activity-card {
            grid-column: span 1;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="h2">Dashboard Overview</h1>
        <p class="text-text-secondary">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
    <div class="flex gap-3">
        <button class="btn-modern btn-ghost">
            <i class="material-icons-outlined">download</i>
            Export
        </button>
        <button class="btn-modern btn-gradient-primary">
            <i class="material-icons-outlined">add</i>
            New User
        </button>
    </div>
</div>

<!-- Stats Row -->
<div class="dashboard-grid">
    <x-bento-card
        title="Total Users"
        :value="number_format($stats['total_users'] ?? 0)"
        :trend="['value' => '+12%', 'positive' => true, 'label' => 'vs last month']"
        icon="groups"
        color="primary"
    />

    <x-bento-card
        title="Active Users"
        :value="number_format($stats['active_users'] ?? 0)"
        :trend="['value' => '+8%', 'positive' => true, 'label' => 'vs last month']"
        icon="person_check"
        color="success"
    />

    <x-bento-card
        title="Revenue"
        :value="'$' . number_format($stats['revenue'] ?? 0)"
        :trend="['value' => '+23%', 'positive' => true, 'label' => 'vs last month']"
        icon="payments"
        color="accent"
    />

    <x-bento-card
        title="Online Routers"
        :value="($stats['online_routers'] ?? 0) . '/' . ($stats['total_routers'] ?? 0)"
        :trend="['value' => '99%', 'positive' => true, 'label' => 'uptime']"
        icon="router"
        color="warning"
    />
</div>

<!-- Charts & Activity -->
<div class="dashboard-grid">
    <!-- Traffic Chart -->
    <div class="bento-item chart-card">
        <div class="card-header">
            <h3 class="card-title">Traffic Overview</h3>
            <div class="flex gap-2">
                <button class="btn-modern btn-xs btn-ghost">Day</button>
                <button class="btn-modern btn-xs btn-primary">Week</button>
                <button class="btn-modern btn-xs btn-ghost">Month</button>
            </div>
        </div>
        <div class="card-body">
            <canvas id="trafficChart" style="height: 300px;"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bento-item activity-card">
        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn-link">View All</a>
        </div>
        <div class="card-body">
            <div class="activity-list">
                @foreach($recentActivities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon activity-{{ $activity['type'] ?? 'info' }}">
                            <i class="material-icons-outlined">{{ $activity['icon'] ?? 'info' }}</i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">{{ $activity['title'] }}</p>
                            <p class="activity-time">{{ $activity['time'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-grid">
    <div class="bento-item col-span-2">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-4 gap-4">
                <button class="btn-modern btn-outline-primary" onclick="window.location.href='{{ route('mikrotik-suite.netfusion.users.create') }}'">
                    <i class="material-icons-outlined">person_add</i>
                    <span>Add User</span>
                </button>
                <button class="btn-modern btn-outline-success" onclick="window.location.href='{{ route('mikrotik-suite.netfusion.hotspot.wizard') }}'">
                    <i class="material-icons-outlined">wifi</i>
                    <span>Hotspot</span>
                </button>
                <button class="btn-modern btn-outline-warning" onclick="window.location.href='{{ route('mikrotik-suite.system.first-time-wizard') }}'">
                    <i class="material-icons-outlined">settings</i>
                    <span>Config</span>
                </button>
                <button class="btn-modern btn-outline-error" onclick="window.location.href='{{ route('mikrotik-suite.netfusion.reports.index') }}'">
                    <i class="material-icons-outlined">assessment</i>
                    <span>Reports</span>
                </button>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bento-item col-span-2">
        <div class="card-header">
            <h3 class="card-title">System Status</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm">CPU Usage</span>
                    <div class="flex items-center gap-2">
                        <div class="w-48 bg-bg-tertiary rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: 45%"></div>
                        </div>
                        <span class="text-sm font-medium">45%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Memory Usage</span>
                    <div class="flex items-center gap-2">
                        <div class="w-48 bg-bg-tertiary rounded-full h-2">
                            <div class="bg-success h-2 rounded-full" style="width: 62%"></div>
                        </div>
                        <span class="text-sm font-medium">62%</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Disk Usage</span>
                    <div class="flex items-center gap-2">
                        <div class="w-48 bg-bg-tertiary rounded-full h-2">
                            <div class="bg-warning h-2 rounded-full" style="width: 78%"></div>
                        </div>
                        <span class="text-sm font-medium">78%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
<script>
// Traffic Chart
const trafficCtx = document.getElementById('trafficChart');
if (trafficCtx) {
    new Chart(trafficCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Download',
                data: [1200, 1900, 3000, 5000, 2000, 3000, 4500],
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 0,
                pointHoverRadius: 6
            }, {
                label: 'Upload',
                data: [800, 1200, 2000, 3000, 1500, 2000, 3000],
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        color: '#cbd5e1',
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                    },
                    ticks: {
                        color: '#94a3b8',
                        callback: function(value) {
                            return (value / 1000) + ' GB';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
