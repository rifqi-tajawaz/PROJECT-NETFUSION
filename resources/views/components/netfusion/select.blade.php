@props([
    'label' => null,
    'name',
    'icon' => null,
    'error' => null
])

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label small text-muted fw-semibold mb-1">{{ $label }}</label>
    @endif
    
    <div class="input-group">
        @if($icon)
            <span class="input-group-text bg-white border border-secondary border-opacity-25 border-end-0">
                <i class="bi bi-{{ $icon }} text-muted"></i>
            </span>
        @endif
        
        <select 
            name="{{ $name }}" 
            id="{{ $name ?? $attributes->get('id') }}"
            {{ $attributes->merge([
                'class' => 'form-select border border-secondary border-opacity-25 py-2 ' . ($icon ? 'border-start-0 ' : '') . ($errors->has($name) ? 'is-invalid' : '')
            ]) }}
        >
            {{ $slot }}
        </select>

        @error($name)
            <div class="invalid-feedback small">{{ $message }}</div>
        @enderror
    </div>
</div>
