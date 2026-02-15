<div class="flex items-center justify-between px-1 py-1">

    {{-- Search Bar (Floating) --}}
    <div class="flex-1 max-w-lg">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="material-icons-outlined text-slate-400 group-focus-within:text-indigo-500 transition-colors">search</i>
            </div>
            <input type="text"
                   class="block w-full pl-11 pr-4 py-3 bg-white border-0 rounded-[2rem] text-sm text-slate-600 placeholder-slate-400 shadow-[0_4px_20px_rgb(0,0,0,0.03)] focus:ring-2 focus:ring-indigo-100 transition-all"
                   placeholder="Search anything...">
        </div>
    </div>

    {{-- Right Actions --}}
    <div class="flex items-center gap-4">

        {{-- Notification Bell --}}
        <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-500 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:text-indigo-600 hover:shadow-md transition-all relative">
            <i class="material-icons-outlined text-xl">notifications</i>
            <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        {{-- Settings / Quick Actions --}}
        <button class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-500 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="material-icons-outlined text-xl">settings</i>
        </button>

        {{-- Mobile Menu Toggle --}}
        <button class="md:hidden w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-indigo-600/30">
            <i class="material-icons-outlined text-xl">menu</i>
        </button>
    </div>
</div>
