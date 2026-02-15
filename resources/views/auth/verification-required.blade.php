@extends('layouts.guest')
@section('title', 'Device Verification Required')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 bg-[#F0F2F5] relative overflow-hidden">

    {{-- Background Blobs --}}
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-amber-400/20 rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-orange-400/20 rounded-full blur-[100px]"></div>

    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative z-10 border border-white/50">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center text-amber-500 mx-auto mb-6 shadow-sm border border-amber-50">
                <i class="material-icons-outlined text-4xl">shield</i>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Device Verification</h3>
            <p class="text-slate-500 text-sm">We noticed a login from a new device.</p>
            @if(session('auth.pending_verification.email'))
                <p class="font-bold text-slate-700 mt-1 bg-slate-100 py-1 px-3 rounded-full text-xs inline-block">{{ session('auth.pending_verification.email') }}</p>
            @endif
            <p class="text-slate-400 text-xs mt-4 leading-relaxed">Please enter the 6-digit verification code sent to your email to continue.</p>
        </div>

        @if (session('error'))
            <div class="bg-red-50 text-red-600 border border-red-100 rounded-2xl p-4 mb-6 text-sm flex items-center gap-2">
                <i class="material-icons-outlined text-lg">error</i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl p-4 mb-6 text-sm flex items-center gap-2">
                <i class="material-icons-outlined text-lg">check_circle</i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verify-device') }}" class="space-y-6">
            @csrf

            <div class="space-y-2 text-center">
                <label for="verification_code" class="text-xs font-bold text-slate-400 uppercase tracking-widest">Enter Code</label>
                <input type="text"
                    class="block w-full py-4 bg-slate-50 border-0 rounded-2xl text-slate-800 placeholder-slate-300 focus:ring-2 focus:ring-amber-500/20 focus:bg-white transition-all font-bold text-3xl tracking-[0.5em] text-center shadow-inner"
                    id="verification_code" name="verification_code" placeholder="------" maxlength="6" pattern="\d{6}"
                    required autofocus autocomplete="off">
                @error('verification_code')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                Verify Device
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center pt-6 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-slate-600 text-sm font-medium transition-colors flex items-center justify-center gap-1 mx-auto">
                    <i class="material-icons-outlined text-sm">logout</i> Cancel & Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById('verification_code').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
@endpush
