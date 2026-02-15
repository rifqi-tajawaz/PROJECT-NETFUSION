@extends('layouts.app')

@section('title', __('netfusion.add_user'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-slate-500 font-medium">
            <a href="{{ route('mikrotik-suite.netfusion.users.index') }}" class="hover:text-indigo-600 transition-colors">Users</a>
            <i class="material-icons-outlined text-xs">chevron_right</i>
            <span class="text-indigo-600">Add New</span>
        </div>
        <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 shadow-sm hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="material-icons-outlined text-lg">close</i>
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">

        {{-- Decorative Header --}}
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

        <div class="p-8 md:p-10">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">New Hotspot User</h2>
                <p class="text-slate-500 mt-1">Create a new user with specific bandwidth limits and profile settings.</p>
            </div>

            <form action="{{ route('mikrotik-suite.netfusion.users.store') }}" method="POST" class="space-y-8">
                @csrf

                {{-- Section: Credentials --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-8 h-[1px] bg-indigo-200"></span> Credentials
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Username --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Username <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">person</i>
                                </div>
                                <input type="text" name="username" required
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner"
                                    placeholder="e.g. user8832">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">key</i>
                                </div>
                                <input type="text" name="password" required
                                    class="block w-full pl-11 pr-12 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner"
                                    placeholder="Combination of letters">
                                <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors" title="Generate Random">
                                    <i class="material-icons-outlined">autorenew</i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Profile --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Profile <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">badge</i>
                                </div>
                                <select name="profile" required
                                    class="block w-full pl-11 pr-10 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner appearance-none cursor-pointer">
                                    <option value="" disabled selected>Select Profile...</option>
                                    @foreach($profiles as $profile)
                                        <option value="{{ $profile['name'] }}">{{ $profile['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400">expand_more</i>
                                </div>
                            </div>
                        </div>

                        {{-- Server --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Server <span class="text-slate-400 text-xs font-normal">(Optional)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">router</i>
                                </div>
                                <select name="server"
                                    class="block w-full pl-11 pr-10 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner appearance-none cursor-pointer">
                                    <option value="">All Servers</option>
                                    @foreach($servers as $server)
                                        <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400">expand_more</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Limits --}}
                <div class="space-y-6 pt-4 border-t border-slate-50">
                    <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-8 h-[1px] bg-indigo-200"></span> Limits & Metadata
                    </h3>

                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Time Limit --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Time Limit</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">timer</i>
                                </div>
                                <input type="text" name="limit_uptime"
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner"
                                    placeholder="e.g. 1h, 30m, 1d">
                            </div>
                            <p class="text-[10px] text-slate-400 ml-1">Format: 1d (days), 1h (hours). Empty = Unlimited.</p>
                        </div>

                        {{-- Data Limit --}}
                        <div class="space-y-2 group">
                            <label class="text-sm font-semibold text-slate-600 ml-1">Data Limit</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">download</i>
                                    </div>
                                    <input type="number" name="limit_bytes_total"
                                        class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-0 rounded-2xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner"
                                        placeholder="Size">
                                </div>
                                <select name="limit_bytes_unit" class="w-24 bg-slate-100 border-0 rounded-2xl text-sm font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 cursor-pointer text-center">
                                    <option value="MB">MB</option>
                                    <option value="GB">GB</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Comment --}}
                    <div class="space-y-2 group">
                        <label class="text-sm font-semibold text-slate-600 ml-1">Comment / Note</label>
                        <textarea name="comment" rows="3"
                            class="block w-full p-4 bg-slate-50 border-0 rounded-2xl text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white transition-all font-medium shadow-inner resize-none"
                            placeholder="Add optional notes about this user..."></textarea>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex flex-col md:flex-row items-center gap-4 justify-end">
                    <a href="{{ route('mikrotik-suite.netfusion.users.index') }}"
                       class="w-full md:w-auto px-8 py-3.5 rounded-2xl text-slate-500 font-bold hover:bg-slate-50 transition-colors text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="w-full md:w-auto px-10 py-3.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="material-icons-outlined">check</i>
                        Create User
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
