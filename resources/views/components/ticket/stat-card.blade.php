@props([
    'label' => '',
    'value' => '',
    'icon' => '',
    'color' => 'primary',
    'variant' => 'light',
])

@php
    $colorClasses = [
        'primary' => 'text-primary bg-primary bg-opacity-10',
        'success' => 'text-success bg-success bg-opacity-10',
        'secondary' => 'text-secondary bg-secondary bg-opacity-10',
        'warning' => 'text-warning bg-warning bg-opacity-10',
        'danger' => 'text-danger bg-danger bg-opacity-10',
        'info' => 'text-info bg-info bg-opacity-10',
    ];
    $valueColor = [
        'primary' => 'text-primary',
        'success' => 'text-success',
        'secondary' => 'text-secondary',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
    ];
@endphp

<div class="admin-stat-card">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="stat-label">{{ $label }}</h6>
        <div class="stat-icon {{ $colorClasses[$color] ?? $colorClasses['primary'] }}">
            <span class="material-icons-outlined fs-4">{{ $icon }}</span>
        </div>
    </div>
    <div class="stat-value {{ $valueColor[$color] ?? $valueColor['primary'] }}">{{ $value }}</div>
</div>
