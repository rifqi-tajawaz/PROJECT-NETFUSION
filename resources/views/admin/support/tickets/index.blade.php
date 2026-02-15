@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', __('admin.tickets'))

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 d-flex align-items-center">
                        <span class="material-icons-outlined text-primary me-2">confirmation_number</span>
                        Support Tickets Management
                    </h4>
                    <p class="text-secondary mb-0">Monitor and manage all customer support tickets</p>
                </div>
                <a href="{{ route('admin.support.tickets.create') }}"
                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center">
                    <span class="material-icons-outlined me-2">add</span> Create Ticket
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Total Tickets</h6>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="material-icons-outlined fs-4">receipt_long</span>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ number_format($stats['total']) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-primary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>12% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Open Tickets</h6>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="material-icons-outlined fs-4">mail</span>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ number_format($stats['open']) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-success">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>8% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Critical Issues</h6>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <span class="material-icons-outlined fs-4">error</span>
                        </div>
                    </div>
                    <div class="stat-value text-danger">{{ number_format($stats['critical']) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-danger">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_down</span>
                        <span>5% decrease</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Resolved</h6>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <span class="material-icons-outlined fs-4">check_circle</span>
                        </div>
                    </div>
                    <div class="stat-value text-info">{{ number_format($stats['closed']) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-info">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>15% increase</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="row g-4">

            {{-- Tickets List --}}
            <div class="col-12 col-xl-8">
                <div class="admin-table-card">
                    {{-- Header with Filters --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-0 d-flex align-items-center">
                                    <span class="material-icons-outlined text-primary me-2">list_alt</span>
                                    All Support Tickets
                                </h5>
                            </div>
                            <span class="badge bg-light text-secondary border">{{ count($tickets) }} tickets</span>
                        </div>

                        <form action="{{ route('admin.support.tickets.index') }}" method="GET"
                            class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="search" class="form-control ps-5"
                                    placeholder="Search tickets by subject or ID..." value="{{ request('search') }}">
                                <span
                                    class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">search</span>
                            </div>
                            <div style="min-width: 150px;">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed
                                    </option>
                                </select>
                            </div>
                            <div style="min-width: 140px;">
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }}>
                                        Critical</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">filter_list</span>
                                    Filter
                                </button>
                                @if(request('search') || request('status') || request('priority'))
                                    <a href="{{ route('admin.support.tickets.index') }}" class="btn btn-light border"
                                        data-bs-toggle="tooltip" title="Clear Filters">
                                        <span class="material-icons-outlined">close</span>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Ticket</th>
                                        <th class="px-4 py-3">Customer</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Priority</th>
                                        <th class="px-4 py-3 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                        <x-admin.ticket.table-row :ticket="$ticket" />
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="mb-3">
                                                    <span class="material-icons-outlined text-secondary"
                                                        style="font-size: 64px; opacity: 0.5;">search_off</span>
                                                </div>
                                                <h5 class="fw-bold text-secondary mb-2">No Tickets Found</h5>
                                                <p class="text-secondary mb-4">Try adjusting your search or filters to find what
                                                    you're looking for.</p>
                                                <a href="{{ route('admin.support.tickets.index') }}"
                                                    class="btn btn-primary rounded-pill px-4">
                                                    Clear Filters
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($tickets, 'hasPages') && $tickets->hasPages())
                        <div class="card-footer bg-white border-top p-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-xl-4">

                {{-- Performance Metrics --}}
                <div class="card rounded-3 border shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-primary me-2">insights</span>
                            Performance Metrics
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                <div>
                                    <div class="small text-secondary mb-1">Avg Response Time</div>
                                    <h5 class="fw-bold mb-0">2.4 hrs</h5>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">Good</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                <div>
                                    <div class="small text-secondary mb-1">Resolution Rate</div>
                                    <h5 class="fw-bold mb-0">94.5%</h5>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">Excellent</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                                <div>
                                    <div class="small text-secondary mb-1">Satisfaction Score</div>
                                    <h5 class="fw-bold mb-0">4.8/5.0</h5>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary">High</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small text-secondary mb-1">Tickets Today</div>
                                    <h5 class="fw-bold mb-0">{{ number_format($stats['today'] ?? 0) }}</h5>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card rounded-3 border shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-warning me-2">flash_on</span>
                            Quick Actions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.support.tickets.create') }}"
                                class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-primary">add</span>
                                <div>
                                    <div class="fw-semibold">New Ticket</div>
                                    <small class="text-secondary">Create support ticket</small>
                                </div>
                            </a>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-warning">trending_up</span>
                                <div>
                                    <div class="fw-semibold">Prioritize</div>
                                    <small class="text-secondary">Sort by priority</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-info">archive</span>
                                <div>
                                    <div class="fw-semibold">Archive</div>
                                    <small class="text-secondary">View closed tickets</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="card rounded-3 border shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-info me-2">history</span>
                            Recent Activity
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">
                            @php
                                $activities = [
                                    ['icon' => 'check_circle', 'text' => 'Ticket #1234 resolved', 'time' => '2 min ago', 'color' => 'success'],
                                    ['icon' => 'mail', 'text' => 'New ticket #1235 created', 'time' => '15 min ago', 'color' => 'primary'],
                                    ['icon' => 'person', 'text' => 'Ticket #1236 assigned', 'time' => '30 min ago', 'color' => 'info'],
                                    ['icon' => 'archive', 'text' => 'Ticket #1230 archived', 'time' => '1 hr ago', 'color' => 'secondary'],
                                ];
                            @endphp
                            @foreach($activities as $activity)
                                <div class="d-flex align-items-start gap-3 p-2 rounded-3 transition-200 hover-bg-light">
                                    <div class="bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} p-2 rounded-circle"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-icons-outlined"
                                            style="font-size: 16px;">{{ $activity['icon'] }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-semibold">{{ $activity['text'] }}</div>
                                        <div class="small text-secondary">{{ $activity['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush