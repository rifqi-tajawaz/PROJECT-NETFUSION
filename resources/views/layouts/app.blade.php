<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | NetFusion by Tajawaz Solutions</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is-admin" content="{{ Auth::check() && Auth::user()->isAdmin() ? '1' : '0' }}">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

{{-- Premium Lock Overlay Logic --}}
@php
    $isPremiumLocked = false;
    if (request()->routeIs('mikrotik-suite.*')) {
        $user = Auth::user();
        if ($user && !$user->isAdmin() && $user->membership_status !== 'active') {
            $isPremiumLocked = true;
        }
    }
@endphp

<body class="bg-slate-900 text-slate-100 font-sans antialiased h-screen flex overflow-hidden {{ $isPremiumLocked ? 'overflow-hidden' : '' }}">

    @if(session('impersonated_by'))
        <div class="fixed top-0 left-0 w-full bg-yellow-500 text-slate-900 text-center py-2 z-[99999] shadow-lg font-medium text-sm flex items-center justify-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-lg">👻</span>
                <span>Ghost Mode: Impersonating <strong>{{ session('impersonated_user_name', auth()->user()->name) }}</strong></span>
            </div>
            <form action="{{ route('admin.users.stop-impersonation') }}" method="POST">
                @csrf
                <button type="submit" class="bg-slate-900 text-white px-3 py-1 rounded hover:bg-slate-800 transition-colors text-xs font-bold uppercase tracking-wider">
                    Exit
                </button>
            </form>
        </div>
    @endif

    @if($isPremiumLocked)
        <x-premium-overlay />
    @endif

    {{-- Sidebar Container --}}
    <aside class="w-64 bg-slate-800 border-r border-slate-700 flex-shrink-0 hidden md:flex flex-col transition-all duration-300">
        @include('layouts.sidebar')
    </aside>

    {{-- Main Content Wrapper --}}
    <div class="flex-1 flex flex-col h-full relative overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-slate-800/50 backdrop-blur-md border-b border-slate-700 h-16 flex items-center px-6 flex-shrink-0 z-20">
            @include('layouts.topbar')
        </header>

        {{-- Scrollable Main Content --}}
        <main class="flex-1 overflow-y-auto p-6 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-transparent">
            @include('layouts.alerts')

            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>

            @unless(View::hasSection('hide_footer'))
                <footer class="mt-12 py-6 border-t border-slate-800 text-center text-slate-500 text-sm">
                    @include('layouts.footer')
                </footer>
            @endunless
        </main>

        {{-- Mobile Overlay --}}
        <div class="md:hidden fixed inset-0 bg-black/50 z-30 hidden" id="mobile-overlay"></div>
    </div>

    {{-- Global Loader --}}
    <div id="global-loader" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[9999] hidden items-center justify-center">
        <div class="w-12 h-12 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    @include('layouts.common-scripts')

    <!-- Third Party Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>
