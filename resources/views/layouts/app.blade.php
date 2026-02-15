<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | NetFusion</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is-admin" content="{{ Auth::check() && Auth::user()->isAdmin() ? '1' : '0' }}">
    <link rel="icon" href="{{ URL::asset('build/images/favicon-32x32.png') }}" type="image/png">

    {{-- Google Fonts: Poppins for that modern app look --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
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

<body class="bg-[#F0F2F5] text-slate-700 h-screen flex overflow-hidden selection:bg-indigo-500 selection:text-white relative">

    {{-- Ghost Mode Banner --}}
    @if(session('impersonated_by'))
        <div class="fixed top-0 left-0 w-full bg-indigo-600 text-white text-center py-2 z-[99999] shadow-md font-medium text-xs flex items-center justify-center gap-4">
            <div class="flex items-center gap-2">
                <span>👻 Ghost Mode: Impersonating <strong>{{ session('impersonated_user_name', auth()->user()->name) }}</strong></span>
            </div>
            <form action="{{ route('admin.users.stop-impersonation') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-full transition-colors text-[10px] font-bold uppercase tracking-wider">
                    Exit
                </button>
            </form>
        </div>
    @endif

    @if($isPremiumLocked)
        <x-premium-overlay />
    @endif

    {{-- Layout Container --}}
    <div class="flex w-full h-full p-4 gap-4 relative">

        {{-- Floating Sidebar --}}
        <aside class="w-72 bg-white/80 glass-effect rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex-shrink-0 hidden md:flex flex-col h-full border border-white/50 z-20 transition-all duration-300">
            @include('layouts.sidebar')
        </aside>

        {{-- Main Content Wrapper --}}
        <div class="flex-1 flex flex-col h-full min-w-0 relative">

            {{-- Floating Topbar --}}
            <header class="mb-4 rounded-[2rem] z-20">
                @include('layouts.topbar')
            </header>

            {{-- Scrollable Content Area --}}
            <main class="flex-1 overflow-y-auto scrollbar-hide rounded-[2rem]">
                @include('layouts.alerts')

                {{-- Content Container --}}
                <div class="h-full pb-6">
                    @yield('content')
                </div>

                @unless(View::hasSection('hide_footer'))
                    <footer class="mt-4 py-4 text-center text-slate-400 text-xs font-medium">
                        @include('layouts.footer')
                    </footer>
                @endunless
            </main>
        </div>
    </div>

    {{-- Mobile Overlay --}}
    <div class="md:hidden fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-30 hidden transition-opacity" id="mobile-overlay"></div>

    {{-- Global Loader --}}
    <div id="global-loader" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-[9999] hidden items-center justify-center">
        <div class="flex flex-col items-center gap-3">
            <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-indigo-500 font-semibold text-sm tracking-wide">Loading...</span>
        </div>
    </div>

    @include('layouts.common-scripts')

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>
