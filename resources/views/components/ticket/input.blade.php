@props([
    'name' => '',
    'type' => 'text',
    'label' => '',
    'placeholder' => '',
    'icon' => null,
    'required' => false,
    'value' => null,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-bold small text-muted">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    @if($type === 'textarea')
        <textarea
            class="form-control @error($name) is-invalid @enderror"
            id="{{ $name }}" name="{{ $name }}" rows="8"
            placeholder="{{ $placeholder }}" @if($required) required @endif>{{ $value }}</textarea>
    @else
        <div class="input-group">
            @if($icon)
                <span class="input-group-text bg-white border-end-0 @error($name) border-danger @enderror">
                    <span class="material-icons-outlined text-secondary">{{ $icon }}</span>
                </span>
            @endif
            <input type="{{ $type }}"
                class="form-control @if($icon) bg-white border-start-0 @endif @error($name) is-invalid @enderror"
                id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
                placeholder="{{ $placeholder }}" @if($required) required @endif>
            @if($icon)
                @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
            @else
                @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
            @endif
        </div>
    @endif
    @if($type === 'textarea')
        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @endif
</div>
