@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', 'My Tickets')

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 d-flex align-items-center">
                        <span class="material-icons-outlined text-primary me-2">confirmation_number</span>
                        My Support Tickets
                    </h4>
                    <p class="text-secondary mb-0">Track and manage all your support requests</p>
                </div>
                @if(($stats['total'] ?? 0) > 0)
                    <a href="{{ route('support.ticket') }}"
                        class="btn btn-brand rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center transition-hover glow-effect">
                        <span class="material-icons-outlined me-2">add</span> New Ticket
                    </a>
                @endif
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <x-ticket-stat-card label="Total Tickets" :value="$stats['total']" icon="receipt_long" color="primary" />
            </div>
            <div class="col-12 col-md-4">
                <x-ticket-stat-card label="Active / Open" :value="$stats['open']" icon="mail" color="success" />
            </div>
            <div class="col-12 col-md-4">
                <x-ticket-stat-card label="Closed / Solved" :value="$stats['closed']" icon="check_circle"
                    color="secondary" />
            </div>
        </div>

        {{-- Tickets Table --}}
        <div class="row">
            <div class="col-12">
                <div class="admin-table-card">
                    {{-- Filters --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <form action="{{ route('ticket.index') }}" method="GET"
                            class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
                            <div style="min-width: 160px;">
                                <select name="status" class="form-select text-secondary filter-select">
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
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="search" class="form-control ps-5"
                                    placeholder="Search by subject or ticket ID..." value="{{ request('search') }}">
                                <span
                                    class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">search</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">filter_list</span>
                                    Filter
                                </button>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('ticket.index') }}" class="btn btn-light border" data-bs-toggle="tooltip"
                                        title="Clear Filters">
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
                                        <th class="px-4 py-3" style="width: 10%;">ID</th>
                                        <th class="px-4 py-3" style="width: 40%;">Subject</th>
                                        <th class="px-4 py-3" style="width: 15%;">Department</th>
                                        <th class="px-4 py-3" style="width: 15%;">Status</th>
                                        <th class="px-4 py-3 text-end" style="width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                        <x-ticket-table-row :ticket="$ticket" />
                                    @empty
                                        <x-ticket-empty-state :show="true" title="No tickets found"
                                            description="You haven't created any support tickets yet. Our team is here to help you with any issues or questions you may have."
                                            buttonText="Create New Ticket" :buttonLink="route('support.ticket')" />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if($tickets->hasPages())
                        <div class="card-footer bg-white border-top p-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/pages/support/my-tickets.js'])
@endpush