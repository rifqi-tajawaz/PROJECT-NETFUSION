@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 bg-[#F0F2F5] relative overflow-hidden">

    {{-- Background Blobs --}}
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-400/20 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-400/20 rounded-full blur-[100px]"></div>

    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative z-10 border border-white/50">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 mx-auto mb-6 transform rotate-3">
                <span class="font-bold text-3xl">N</span>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Welcome Back</h2>
            <p class="text-slate-500 text-sm">Sign in to access your NetFusion dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email --}}
            <div class="space-y-2">
                <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">email</i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full pl-11 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-600 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium"
                        placeholder="name@company.com">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Forgot?</a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">lock</i>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="block w-full pl-11 pr-4 py-4 bg-slate-50 border-0 rounded-2xl text-slate-600 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center ml-1">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded-lg border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500/20 w-5 h-5 cursor-pointer">
                <label for="remember_me" class="ml-3 block text-sm text-slate-600 font-medium cursor-pointer">
                    Keep me logged in
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-600/30 transform hover:-translate-y-0.5 transition-all duration-200">
                Sign In
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center">
            <p class="text-slate-500 text-sm">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 ml-1">Create Account</a>
            </p>
        </div>
    </div>
</div>
@endsection
