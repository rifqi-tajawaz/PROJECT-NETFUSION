@props([
    'title' => '',
    'action' => '',
    'method' => 'POST',
    'fields' => [],
    'btnText' => 'Submit',
    'btnTheme' => 'primary',
    'theme' => 'primary',
])

@php
    $themes = [
        'primary' => ['gradient' => 'linear-gradient(135deg, rgba(95, 45, 237, 0.08) 0%, rgba(95, 45, 237, 0.02) 100%)', 'icon' => '#5f2ded'],
        'success' => ['gradient' => 'linear-gradient(135deg, rgba(25, 135, 84, 0.08) 0%, rgba(25, 135, 84, 0.02) 100%)', 'icon' => '#198754'],
        'warning' => ['gradient' => 'linear-gradient(135deg, rgba(255, 193, 7, 0.08) 0%, rgba(255, 193, 7, 0.02) 100%)', 'icon' => '#ffc107'],
        'info' => ['gradient' => 'linear-gradient(135deg, rgba(13, 202, 240, 0.08) 0%, rgba(13, 202, 240, 0.02) 100%)', 'icon' => '#0dcaf0'],
    ];
    $t = $themes[$theme] ?? $themes['primary'];
@endphp

<div class="bento-card bento-2x2 {{ $theme }} {{ $attributes->get('class') ?? '' }}">
    
    <div class="bento-decoration top-right">
        <span class="bento-decoration-icon {{ $theme }}">
            edit_note
        </span>
    </div>

    <div class="bento-flex-center mb-4">
        <div class="bento-icon {{ $theme }}">
            <span class="material-icons-outlined">edit_note</span>
        </div>
        <h4 class="bento-title mb-0">{{ $title }}</h4>
    </div>

    <form action="{!! $action !!}" method="{{ $method }}">
        @csrf
        <div class="row g-3">
            @foreach($fields as $field)
                <div class="{{ $field['col'] ?? 'col-12' }}">
                    @if($field['type'] === 'text' || $field['type'] === 'email' || $field['type'] === 'password')
                        <label class="bento-subtitle mb-2">{{ $field['label'] }}</label>
                        <input type="{!! $field['type'] !!}"
                            name="{!! $field['name'] !!}"
                            class="bento-input"
                            placeholder="{!! $field['placeholder'] ?? '' !!}"
                            @if(isset($field['required'])) required @endif
                            @if(isset($field['value'])) value="{!! $field['value'] !!}" @endif>
                    @elseif($field['type'] === 'textarea')
                        <label class="bento-subtitle mb-2">{{ $field['label'] }}</label>
                        <textarea name="{!! $field['name'] !!}"
                            class="bento-textarea"
                            rows="{!! $field['rows'] ?? 4 !!}"
                            placeholder="{!! $field['placeholder'] ?? '' !!}"
                            @if(isset($field['required'])) required @endif>{!! $field['value'] ?? '' !!}</textarea>
                    @elseif($field['type'] === 'select')
                        <label class="bento-subtitle mb-2">{{ $field['label'] }}</label>
                        <select name="{!! $field['name'] !!}" class="bento-select">
                            @foreach($field['options'] as $option)
                                <option value="{!! $option['value'] !!}"
                                    @if(isset($field['value']) && $field['value'] === $option['value']) selected @endif>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="bento-flex-end gap-2 mt-4">
            <button type="reset" class="bento-btn ghost">Reset</button>
            <button type="submit" class="bento-btn {{ $btnTheme }}">
                {{ $btnText }}
                <span class="material-icons-outlined fs-6">send</span>
            </button>
        </div>
    </form>

    <div class="bento-glow {{ $theme }}"></div>
</div>
