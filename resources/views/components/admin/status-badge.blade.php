@props([
    'status' => '',
    'statusMap' => [],
])

@php
    $defaultStatusMap = [
        'open' => 'success',
        'closed' => 'secondary',
        'pending' => 'warning',
        'in_progress' => 'info',
        'active' => 'success',
        'inactive' => 'secondary',
    ];
    $map = !empty($statusMap) ? $statusMap : $defaultStatusMap;
    $color = $map[$status] ?? 'secondary';
@endphp

<span class="badge badge-soft-{{ $color }} rounded-pill px-3 py-2">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
