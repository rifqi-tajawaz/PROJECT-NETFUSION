@props([
    'ticket' => null,
    'assigned' => false,
])

@php
    $statusColors = [
        'open' => 'success',
        'closed' => 'secondary',
        'pending' => 'warning',
        'in_progress' => 'info',
    ];
    $priorityColors = [
        'Low' => 'success',
        'Medium' => 'primary',
        'High' => 'danger',
    ];
    $statusColor = $statusColors[$ticket->status] ?? 'secondary';
    $priorityColor = $priorityColors[$ticket->priority] ?? 'secondary';
@endphp

<div class="card border rounded-3 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <span class="badge badge-soft-{{ $statusColor }} rounded-pill px-3 py-2 mb-2 d-inline-block">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
                <h5 class="fw-bold mb-0">{{ $ticket->subject }}</h5>
            </div>
            @if($assigned)
                <div class="avatar bg-primary text-white">
                    <span class="fw-bold">A</span>
                </div>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">person</span>
                    <div>
                        <small class="text-secondary d-block">Customer</small>
                        <span class="fw-bold text-dark">{{ $ticket->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">email</span>
                    <div>
                        <small class="text-secondary d-block">Email</small>
                        <span class="fw-bold text-dark">{{ $ticket->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">build</span>
                    <div>
                        <small class="text-secondary d-block">Department</small>
                        <span class="fw-bold text-dark">{{ $ticket->department }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">priority_high</span>
                    <div>
                        <small class="text-secondary d-block">Priority</small>
                        <span class="badge badge-soft-{{ $priorityColor }} px-2 py-1">{{ $ticket->priority }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">event</span>
                    <div>
                        <small class="text-secondary d-block">Created</small>
                        <span class="fw-bold text-dark">{{ $ticket->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-icons-outlined text-secondary">update</span>
                    <div>
                        <small class="text-secondary d-block">Updated</small>
                        <span class="fw-bold text-dark">{{ $ticket->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
