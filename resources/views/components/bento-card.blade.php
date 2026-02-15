@props([
    'title' => null,
    'value' => null,
    'description' => null,
    'trend' => null,
    'icon' => null,
    'color' => 'primary',
    'gradient' => false,
    'colspan' => 1,
    'rowspan' => 1,
])

<div class="bento-item
    {{ $colspan == 2 ? 'col-span-2' : '' }}
    {{ $colspan == 3 ? 'col-span-3' : '' }}
    {{ $colspan == 4 ? 'col-span-4' : '' }}
    {{ $rowspan == 2 ? 'row-span-2' : '' }}
    card-modern
    {{ $gradient ? 'card-gradient-' . $color : '' }}"
>
    @if($icon)
        <div class="card-stat">
            <div class="stat-icon icon-{{ $color }}">
                <i class="material-icons-outlined">{{ $icon }}</i>
            </div>
            <div class="stat-content">
                @if($title)
                    <p class="stat-label">{{ $title }}</p>
                @endif
                @if($value)
                    <p class="stat-value">{{ $value }}</p>
                @endif
                @if($trend)
                    <div class="stat-trend {{ $trend['positive'] ? 'trend-up' : 'trend-down' }}">
                        <i class="material-icons-outlined">
                            {{ $trend['positive'] ? 'trending_up' : 'trending_down' }}
                        </i>
                        <span>{{ $trend['value'] }}</span>
                        @if($trend['label'])
                            <span class="text-text-muted">{{ $trend['label'] }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            <div class="card-menu">
                <button>
                    <i class="material-icons-outlined">more_vert</i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($value)
                <p class="card-value">{{ $value }}</p>
            @endif
            @if($description)
                <p class="card-description">{{ $description }}</p>
            @endif
        </div>
        @if($trend)
            <div class="card-footer">
                <div class="card-trend {{ $trend['positive'] ? 'trend-up' : 'trend-down' }}">
                    <i class="material-icons-outlined">
                        {{ $trend['positive'] ? 'trending_up' : 'trending_down' }}
                    </i>
                    <span>{{ $trend['value'] }}</span>
                    @if($trend['label'])
                        <span>vs last month</span>
                    @endif
                </div>
                <a href="#" class="btn-link">View Details</a>
            </div>
        @endif
    @endif
</div>

@push('styles')
<style>
    .bento-item {
        min-height: {{ $rowspan == 2 ? '400px' : '200px' }};
        padding: var(--spacing-6);
    }
</style>
@endpush
