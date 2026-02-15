@props([
    'ticket' => null,
    'compact' => false,
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
        'Critical' => 'danger',
    ];
    $statusColor = $statusColors[$ticket->status] ?? 'secondary';
    $priorityColor = $priorityColors[$ticket->priority] ?? 'secondary';
    $cardSize = $compact ? 'bento-1x1' : 'bento-2x1';
@endphp

<div class="bento-card clickable {{ $cardSize }} {{ $attributes->get('class') ?? '' }}"
     data-href="{!! route('admin.support.tickets.show', $ticket->id) !!}">
    
    <div class="bento-flex-between mb-3">
        <div>
            <span class="bento-badge {{ $statusColor }} mb-2">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
            <h4 class="bento-subtitle mb-0">{{ Str::limit($ticket->subject, $compact ? 30 : 50) }}</h4>
        </div>
        <div class="bento-icon {{ $priorityColor }} sm">
            <span class="material-icons-outlined">confirmation_number</span>
        </div>
    </div>

    @if(!$compact)
        <div class="bento-flex-start gap-2 mb-3">
            <div class="d-flex align-items-center gap-1">
                <span class="material-icons-outlined fs-6 text-secondary">person</span>
                <span class="bento-small">{{ $ticket->name ?? 'Unknown' }}</span>
            </div>
            <div class="d-flex align-items-center gap-1">
                <span class="material-icons-outlined fs-6 text-secondary">event</span>
                <span class="bento-small">{{ $ticket->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="bento-flex-between">
            <span class="bento-badge {{ $priorityColor }}">
                {{ $ticket->priority }} Priority
            </span>
            <span class="bento-small text-secondary">{{ $ticket->department }}</span>
        </div>
    @endif

    <div class="bento-glow {{ $priorityColor }}"></div>
</div>
