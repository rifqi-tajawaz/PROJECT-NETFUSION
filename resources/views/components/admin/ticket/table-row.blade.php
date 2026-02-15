@props([
    'ticket' => null,
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

<tr>
    <td class="px-4 py-3">
        <span class="fw-bold text-secondary">#{{ $ticket->id }}</span>
    </td>
    <td class="px-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar bg-primary bg-opacity-10 text-primary">
                <span class="fw-bold">{{ substr($ticket->subject, 0, 1) }}</span>
            </div>
            <div>
                <span class="fw-bold text-dark d-block">{{ Str::limit($ticket->subject, 50) }}</span>
                <div class="small text-secondary">
                    By {{ $ticket->name ?? 'Unknown' }} &bull; {{ $ticket->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-4 py-3">
        <span class="badge bg-light text-secondary border fw-normal px-2 py-1">
            {{ $ticket->department }}
        </span>
    </td>
    <td class="px-4 py-3">
        <span class="badge badge-soft-{{ $priorityColor }} fw-medium px-2 py-1">
            {{ $ticket->priority }}
        </span>
    </td>
    <td class="px-4 py-3">
        <span class="badge badge-soft-{{ $statusColor }} rounded-pill px-3 py-2">
            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
        </span>
    </td>
    <td class="px-4 py-3 text-end">
        <a href="{{ route('admin.tickets.show', $ticket->id) }}"
            class="btn btn-sm btn-light border text-primary fw-bold">
            <span class="material-icons-outlined me-1 fs-6">visibility</span> View
        </a>
    </td>
</tr>
