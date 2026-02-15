@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'error' => null
])

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label small text-muted fw-semibold mb-1">{{ $label }}</label>
    @endif
    
    <div class="input-group">
        @if($icon)
            <span class="input-group-text border border-secondary border-opacity-25 border-end-0">
                <i class="bi bi-{{ $icon }} text-muted"></i>
            </span>
        @endif
        
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'form-control border border-secondary border-opacity-25 py-2 ' . ($icon ? 'border-start-0 ' : '') . ($errors->has($name) ? 'is-invalid' : '')
            ]) }}
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
        >

        @error($name)
            <div class="invalid-feedback small">{{ $message }}</div>
        @enderror
    </div>
</div>
