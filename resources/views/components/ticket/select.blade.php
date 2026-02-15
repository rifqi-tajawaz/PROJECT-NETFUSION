@props([
    'name' => '',
    'label' => '',
    'icon' => null,
    'required' => false,
    'options' => [],
    'selected' => null,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    <div class="input-group">
        @if($icon)
            <span class="input-group-text">
                <span class="material-icons-outlined text-secondary">{{ $icon }}</span>
            </span>
        @endif
        <select class="form-select @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}">
            @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
