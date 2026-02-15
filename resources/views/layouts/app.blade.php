<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <script>
        (function () {
            const theme = localStorage.getItem('nf-theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | NetFusion by Tajawaz Solutions</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is-admin" content="{{ Auth::check() && Auth::user()->isAdmin() ? '1' : '0' }}">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png">

    @include('layouts.head-css')
</head>

{{-- Move body tag start to handle dynamic class injection --}}
{{-- Premium Lock Overlay Logic --}}
@php
    $isPremiumLocked = false;
    // Check if current route is within Mikrotik Suite and user is NOT premium/admin
    if (request()->routeIs('mikrotik-suite.*')) {
        $user = Auth::user();
        if ($user && !$user->isAdmin() && $user->membership_status !== 'active') {
            $isPremiumLocked = true;
        }
    }
@endphp

<body class="{{ $isPremiumLocked ? 'is-locked' : '' }}">
    @if(session('impersonated_by'))
        <div class="bg-warning text-dark text-center py-2 fixed-top shadow-lg" style="z-index: 99999;">
            <div class="container d-flex align-items-center justify-content-center gap-3 flex-wrap">
                <i class='bx bx-ghost fs-4'></i>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold">👻 Ghost Mode Active:</span>
                    <span>Impersonating</span>
                    <strong>{{ session('impersonated_user_name', auth()->user()->name) }}</strong>
                    <span class="text-muted">({{ session('impersonated_user_email', auth()->user()->email) }})</span>
                </div>
                @if(session('impersonation_started_at'))
                    <span class="badge bg-dark">
                        <i class='bx bx-time-five'></i>
                        {{ session('impersonation_started_at')->diffForHumans(null, true) }}
                    </span>
                @endif
                <form action="{{ route('admin.users.stop-impersonation') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-dark btn-sm fw-bold px-3">
                        <i class='bx bx-log-out'></i> Exit Ghost Mode
                    </button>
                </form>
            </div>
        </div>
        <div style="height: 56px;"></div> <!-- Spacer for fixed banner -->
    @endif

    @if($isPremiumLocked)
        <x-premium-overlay />
    @endif

    @include('layouts.topbar')

    @include('layouts.sidebar')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content mt-2 mb-4">
            @include('layouts.alerts')
            @yield('content')
        </div>
        @unless(View::hasSection('hide_footer'))
            @include('layouts.footer')
        @endunless
    </main>

    <!--start overlay-->
    <div class="overlay"></div>
    <!--end overlay-->

    <!-- Global Loader -->
    <div id="global-loader"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(2px);">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @include('layouts.common-scripts')

    <!-- Third Party Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>