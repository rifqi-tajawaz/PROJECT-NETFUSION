@extends('layouts.app')

@section('title', __('netfusion.hotspot_users'))

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ __('netfusion.hotspot_users') }}</h1>
            <p class="text-slate-500 text-sm font-medium">{{ __('netfusion.manage_wifi_users') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('mikrotik-suite.netfusion.users.create') }}"
               class="px-5 py-2.5 bg-white text-slate-600 font-bold rounded-2xl shadow-sm border border-slate-100 hover:text-indigo-600 hover:shadow-md transition-all flex items-center gap-2">
               <i class="material-icons-outlined text-lg">add</i>
               {{ __('netfusion.add_user') }}
            </a>
            <a href="{{ route('mikrotik-suite.netfusion.users.generate') }}"
               class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5 transition-all flex items-center gap-2">
               <i class="material-icons-outlined text-lg">qr_code</i>
               {{ __('netfusion.generate_batch') }}
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total', 'value' => $totalCount, 'icon' => 'people', 'color' => 'indigo'],
            ['label' => 'Active', 'value' => $onlineCount, 'icon' => 'wifi', 'color' => 'emerald'],
            ['label' => 'Expired', 'value' => $expiredCount, 'icon' => 'timer_off', 'color' => 'rose'],
            ['label' => 'Disabled', 'value' => $disabledCount, 'icon' => 'block', 'color' => 'slate'],
        ] as $stat)
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-white/50 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 flex items-center justify-center">
                <i class="material-icons-outlined text-xl">{{ $stat['icon'] }}</i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-slate-800">{{ $stat['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">

        {{-- Toolbar --}}
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row gap-4 justify-between items-center bg-slate-50/50">

            {{-- Search --}}
            <div class="relative w-full md:w-72 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">search</i>
                </div>
                <input type="text" id="userSearch"
                       class="block w-full pl-10 pr-4 py-2.5 bg-white border-0 ring-1 ring-slate-200 rounded-xl text-sm text-slate-600 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                       placeholder="Search users...">
            </div>

            {{-- Filters --}}
            <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">

                {{-- Profile Filter --}}
                <div class="relative group">
                    <button class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-600 transition-all flex items-center gap-2 shadow-sm">
                        <span>{{ $selectedProfile ?: 'All Profiles' }}</span>
                        <i class="material-icons-outlined text-sm">expand_more</i>
                    </button>
                    {{-- Dropdown (Simplified for demo, requires JS logic for full interactivity) --}}
                </div>

                {{-- Action Buttons --}}
                <div class="flex bg-white rounded-xl shadow-sm border border-slate-200 p-1">
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Export CSV">
                        <i class="material-icons-outlined text-lg">file_download</i>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors" title="Print Script">
                        <i class="material-icons-outlined text-lg">code</i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                        <th class="p-4 w-10 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="p-4">User Details</th>
                        <th class="p-4">Profile</th>
                        <th class="p-4">MAC Address</th>
                        <th class="p-4">Limit (Time/Data)</th>
                        <th class="p-4">Comment</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50" id="usersTableBody">
                    @forelse($users as $user)
                    <tr class="group hover:bg-indigo-50/30 transition-colors">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs uppercase group-hover:bg-white group-hover:text-indigo-600 group-hover:shadow-sm transition-all">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 group-hover:text-indigo-700 transition-colors">{{ $user->name }}</p>
                                    <p class="text-xs font-mono text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded inline-block mt-0.5">{{ $user->password }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                {{ $user->profile }}
                            </span>
                        </td>
                        <td class="p-4 font-mono text-slate-500 text-xs">{{ $user->macAddress ?? '-' }}</td>
                        <td class="p-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="material-icons-outlined text-[14px] text-slate-400">schedule</i>
                                    <span>{{ $user->uptime }} / {{ $user->limitUptime ?: '∞' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="material-icons-outlined text-[14px] text-slate-400">data_usage</i>
                                    <span>{{ \App\Helpers\Format::bytes($user->limitBytesTotal) ?: '∞' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-slate-500 italic text-xs">{{ Str::limit($user->comment, 20) ?? '-' }}</td>
                        <td class="p-4 text-right">
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-white hover:shadow-sm hover:text-indigo-600 transition-all">
                                <i class="material-icons-outlined text-lg">more_vert</i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-3">
                                <i class="material-icons-outlined text-3xl">search_off</i>
                            </div>
                            <p class="text-slate-500 font-medium">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer/Pagination --}}
        <div class="p-4 border-t border-slate-100 flex justify-between items-center bg-slate-50/50 text-xs text-slate-500 font-medium rounded-b-[2rem]">
            <span>Showing {{ count($users) }} users</span>
            <div class="flex gap-2">
                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:border-indigo-300 hover:text-indigo-600 transition-colors disabled:opacity-50">Previous</button>
                <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:border-indigo-300 hover:text-indigo-600 transition-colors">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
