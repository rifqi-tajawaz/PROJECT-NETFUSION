@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Good Morning, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
            <p class="text-slate-500 mt-1 font-medium">Here's what's happening with your network today.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white rounded-full text-sm font-semibold text-slate-600 shadow-sm border border-slate-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                System Online
            </span>
            <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-slate-400 hover:text-indigo-600 shadow-sm border border-slate-100 transition-colors">
                <i class="material-icons-outlined text-xl">refresh</i>
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Card 1 --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 border border-slate-100 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i class="material-icons-outlined text-2xl">router</i>
                </div>
                <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">+12%</span>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Active Routers</h3>
            <p class="text-3xl font-bold text-slate-800">24</p>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 border border-slate-100 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <i class="material-icons-outlined text-2xl">wifi</i>
                </div>
                <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">+5%</span>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Hotspot Users</h3>
            <p class="text-3xl font-bold text-slate-800">1,204</p>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 border border-slate-100 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-pink-50 flex items-center justify-center text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                    <i class="material-icons-outlined text-2xl">error_outline</i>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">0%</span>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Critical Alerts</h3>
            <p class="text-3xl font-bold text-slate-800">3</p>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 border border-slate-100 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                    <i class="material-icons-outlined text-2xl">speed</i>
                </div>
                <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg">-2%</span>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Avg Latency</h3>
            <p class="text-3xl font-bold text-slate-800">12ms</p>
        </div>
    </div>

    {{-- Main Charts & Activity Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Large Chart Card --}}
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Traffic Overview</h3>
                    <p class="text-sm text-slate-400 font-medium">Daily bandwidth consumption</p>
                </div>
                <select class="bg-slate-50 border-none text-sm font-semibold text-slate-600 rounded-xl py-2 pl-4 pr-8 focus:ring-0 cursor-pointer hover:bg-slate-100 transition-colors">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>

            {{-- Placeholder Chart Area --}}
            <div class="h-64 flex items-end justify-between gap-2 px-2">
                @foreach([40, 65, 45, 80, 55, 70, 50, 60, 75, 45, 65, 55] as $height)
                    <div class="w-full bg-slate-50 rounded-t-xl relative group h-full flex items-end">
                        <div style="height: {{ $height }}%" class="w-full bg-indigo-500 rounded-xl opacity-80 group-hover:opacity-100 group-hover:shadow-[0_0_20px_rgba(99,102,241,0.5)] transition-all duration-300"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 text-xs font-bold text-slate-400 uppercase tracking-wider px-2">
                <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span>
            </div>
        </div>

        {{-- Side Widgets Column --}}
        <div class="space-y-6">

            {{-- Quick Actions --}}
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-[0_20px_40px_-10px_rgba(79,70,229,0.4)] relative overflow-hidden">
                {{-- Decorative Shapes --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[100px]"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 bg-black/10 rounded-tr-[50px]"></div>

                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-1">Quick Generate</h3>
                    <p class="text-indigo-200 text-sm mb-6">Create new hotspot vouchers instantly</p>

                    <button class="w-full bg-white text-indigo-600 font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:bg-indigo-50 transition-all duration-300 flex items-center justify-center gap-2 group">
                        <i class="material-icons-outlined group-hover:scale-110 transition-transform">add_circle</i>
                        Generate Voucher
                    </button>
                </div>
            </div>

            {{-- Activity List --}}
            <div class="bg-white rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex-1">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Recent Activity</h3>
                <div class="space-y-6">
                    @foreach(range(1, 3) as $i)
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                                <i class="material-icons-outlined text-slate-400 text-sm">history</i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-700">User Login</p>
                                <p class="text-xs text-slate-400">2 minutes ago</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
