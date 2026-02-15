<div class="h-full flex flex-col p-6">
    {{-- Header --}}
    <div class="flex items-center gap-3 px-2 mb-8">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
            <span class="font-bold text-xl">N</span>
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-lg text-slate-800 leading-tight tracking-tight">NetFusion</span>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tajawaz</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto scrollbar-hide space-y-1">

        {{-- Dashboard Section --}}
        <div class="mb-6">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Main</p>
            <a href="{{ route('mikrotik-suite.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('mikrotik-suite.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i class="material-icons-outlined text-xl">grid_view</i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
        </div>

        {{-- Modules Section --}}
        <div class="mb-6">
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Modules</p>

            {{-- Hotspot --}}
            <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('mikrotik-suite.netfusion.*') ? 'bg-white text-indigo-600 shadow-md font-semibold' : 'text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('mikrotik-suite.netfusion.*') ? 'bg-indigo-100' : 'bg-slate-100 group-hover:bg-indigo-100' }} transition-colors">
                    <i class="material-icons-outlined text-lg">wifi</i>
                </div>
                <span class="font-medium text-sm">Hotspot</span>
            </a>

            {{-- PPP --}}
            <a href="{{ route('mikrotik-suite.netfusion.ppp.active.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('mikrotik-suite.netfusion.ppp.*') ? 'bg-white text-indigo-600 shadow-md font-semibold' : 'text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('mikrotik-suite.netfusion.ppp.*') ? 'bg-indigo-100' : 'bg-slate-100 group-hover:bg-indigo-100' }} transition-colors">
                    <i class="material-icons-outlined text-lg">router</i>
                </div>
                <span class="font-medium text-sm">PPP</span>
            </a>

            {{-- Monitoring --}}
            <a href="{{ route('mikrotik-suite.monitoring.traffic-monitor') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('mikrotik-suite.monitoring.*') ? 'bg-white text-indigo-600 shadow-md font-semibold' : 'text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm' }}">
                <div class="p-1.5 rounded-lg {{ request()->routeIs('mikrotik-suite.monitoring.*') ? 'bg-indigo-100' : 'bg-slate-100 group-hover:bg-indigo-100' }} transition-colors">
                    <i class="material-icons-outlined text-lg">insights</i>
                </div>
                <span class="font-medium text-sm">Monitoring</span>
            </a>
        </div>

        {{-- Settings Section --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">System</p>

            <a href="{{ route('mikrotik-suite.netfusion.settings.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('mikrotik-suite.netfusion.settings.*') ? 'bg-white text-indigo-600 shadow-md font-semibold' : 'text-slate-500 hover:bg-white hover:text-indigo-600 hover:shadow-sm' }}">
                <i class="material-icons-outlined text-xl">settings</i>
                <span class="font-medium text-sm">Settings</span>
            </a>
        </div>

    </nav>

    {{-- User Card (Bottom) --}}
    <div class="mt-auto pt-6">
        <div class="bg-indigo-50/50 rounded-2xl p-4 flex items-center gap-3 border border-indigo-100">
            @if(optional(Auth::user())->avatar)
                <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" alt="">
            @else
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ strtoupper(substr(optional(Auth::user())->name ?? 'G', 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ optional(Auth::user())->name }}</p>
                <p class="text-xs text-slate-500 truncate">Admin</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="material-icons-outlined text-xl">logout</i>
                </button>
            </form>
        </div>
    </div>
</div>
