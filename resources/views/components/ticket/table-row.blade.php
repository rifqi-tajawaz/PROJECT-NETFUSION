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
    $color = $statusColors[$ticket->status] ?? 'secondary';
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
                <a href="{{ route('ticket.show', $ticket->id) }}"
                    class="text-dark fw-bold text-decoration-none d-block">
                    {{ Str::limit($ticket->subject, 50) }}
                </a>
                <div class="small text-muted">
                    Updated {{ $ticket->updated_at->diffForHumans() }}
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
        <span
            class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2">
            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
        </span>
    </td>
    <td class="px-4 py-3 text-end">
        <a href="{{ route('ticket.show', $ticket->id) }}"
            class="btn btn-sm btn-light border text-primary fw-bold">
            View Ticket <span class="material-icons-outlined ms-1 fs-6">arrow_forward</span>
        </a>
    </td>
</tr>
