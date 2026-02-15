@props([
    'type' => 'button',
    'variant' => 'brand',
    'size' => 'md',
    'icon' => null,
    'loading' => false,
    'rounded' => false,
    'className' => ''
])
@php
    $variants = [
        'brand' => 'btn-brand',
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'white' => 'btn-white',
        'outline' => 'btn-outline-primary'
    ];

    $btnClass = $variants[$variant] ?? 'btn-primary';
    $roundedClass = $rounded ? 'rounded-pill' : '';
@endphp
<button 
type="{{ $type }}" 
    {{ $attributes->merge(['class' => "btn $btnClass $roundedClass shadow-sm transition-hover $className"]) }}
{{ $loading ? 'disabled' : '' }}
>
    @if($loading)
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
    @elseif($icon)
        <i class="bi bi-{{ $icon }} me-2"></i>
    @endif
    
    {{ $slot }}
</button>
