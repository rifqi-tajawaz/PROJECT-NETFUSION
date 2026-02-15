@props([
    'items' => [],
    'section' => null
])

@foreach($items as $item)
    @if(isset($item['section']))
        <!-- Section Label -->
        <div class="sidebar-section">
            <span class="sidebar-section-label">{{ $item['section'] }}</span>
        </div>
    @else
        <!-- Menu Item -->
        @if(!empty($item['children']))
            <!-- Has Submenu -->
        <div class="nav-group">
            <button class="nav-item nav-item-has-children" type="button" data-nav-toggle>
                <div class="nav-icon">
                    <i class="{{ $item['icon'] }}"></i>
                </div>
                <div class="nav-content">
                    <span class="nav-title">{{ $item['title'] }}</span>
                    @if($item['badge'])
                        <span class="nav-badge">{{ $item['badge'] }}</span>
                    @endif
                </div>
                <div class="nav-arrow">
                    <i class="ph ph-caret-down"></i>
                </div>
            </button>

            <div class="nav-submenu">
                @foreach($item['children'] as $child)
                    <a href="{{ $child['route'] }}" class="nav-submenu-item">
                        <i class="{{ $child['icon'] }}"></i>
                        <span>{{ $child['title'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
        @else
            <!-- Single Menu Item -->
        <a href="{{ $item['route'] }}" class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
            <div class="nav-icon">
                <i class="{{ $item['icon'] }}"></i>
            </div>
            <div class="nav-content">
                <span class="nav-title">{{ $item['title'] }}</span>
                @if($item['badge'])
                    <span class="nav-badge">{{ $item['badge'] }}</span>
                @endif
            </div>
        </a>
        @endif
    @endif
@endforeach
