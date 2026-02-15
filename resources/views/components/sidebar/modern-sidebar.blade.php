<!-- Modern Sidebar Component -->
<aside class="modern-sidebar" data-sidebar-state="expanded">
    <!-- Logo Section -->
    <div class="sidebar-header">
        <a href="{{ route('mikrotik-suite.dashboard') }}" class="sidebar-logo">
            <div class="logo-icon">
                <img src="{{ asset('build/images/logo-icon.png') }}" alt="NetFusion" />
            </div>
            <div class="logo-text">
                <span class="logo-title">NetFusion</span>
                <span class="logo-subtitle">Network Management</span>
            </div>
        </a>
        <button class="sidebar-close" data-sidebar-close type="button">
            <i class="ph ph-x"></i>
        </button>
    </div>

    <!-- Search -->
    <div class="sidebar-search">
        <div class="search-input-wrapper">
            <i class="ph ph-magnifying-glass search-icon"></i>
            <input
                type="search"
                placeholder="Search... (/)"
                class="search-input"
                data-sidebar-search
            >
            <kbd class="search-kbd">/</kbd>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="sidebar-scroll">
            @foreach($menuItems as $section)
                <x-sidebar.nav-items :items="$section['items']" />
            @endforeach
        </div>
    </nav>

    <!-- Recent Items -->
    @if(!empty($recentItems))
    <div class="sidebar-recent">
        <div class="recent-header">
            <span class="recent-title">Recent</span>
        </div>
        <div class="recent-list">
            @foreach($recentItems as $item)
            <a href="{{ $item['url'] }}" class="recent-item">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['title'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- User Profile -->
    <div class="sidebar-user">
        @auth
            <a href="{{ route('account.security') }}" class="user-profile">
                <div class="user-avatar">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="user-status"></div>
                </div>
                <div class="user-info">
                    <span class="user-name">{{ $user->name }}</span>
                    <span class="user-role">{{ $user->role?->name ?? 'User' }}</span>
                </div>
                <i class="ph ph-caret-right user-arrow"></i>
            </a>
        @endauth
    </div>

    <!-- Collapse Toggle -->
    <button class="sidebar-toggle" data-sidebar-toggle type="button">
        <i class="ph ph-caret-left"></i>
    </button>
</aside>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-bottom-nav">
    <a href="{{ route('mikrotik-suite.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('mikrotik-suite.dashboard') ? 'active' : '' }}">
        <i class="ph ph-house"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('mikrotik-suite.netfusion.users.index') }}" class="mobile-nav-item {{ request()->routeIs('mikrotik-suite.netfusion.users.*') ? 'active' : '' }}">
        <i class="ph ph-users"></i>
        <span>Users</span>
    </a>
    <a href="{{ route('mikrotik-suite.monitoring.traffic-monitor') }}" class="mobile-nav-item {{ request()->routeIs('mikrotik-suite.monitoring.*') ? 'active' : '' }}">
        <i class="ph ph-activity"></i>
        <span>Monitor</span>
    </a>
    <a href="{{ route('mikrotik-suite.netfusion.settings.index') }}" class="mobile-nav-item {{ request()->routeIs('mikrotik-suite.netfusion.settings.*') ? 'active' : '' }}">
        <i class="ph ph-gear"></i>
        <span>Tools</span>
    </a>
</nav>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
@endpush

@push('scripts')
<script src="{{ asset('build/js/sidebar.js') }}"></script>
@endpush
