<!-- Bottom Navigation (Mobile Only) -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 shadow-lg transition-colors duration-300">
    <div class="flex h-16">
        <a href="{{ route('dashboard') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('dashboard') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>
        <a href="{{ route('transactions.index') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('transactions.*') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span class="text-[10px] font-medium">Transaksi</span>
        </a>
        @if(auth()->user()->isOrangTua())
            <a href="{{ route('debts.index') }}"
                class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('debts.*') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-[10px] font-medium">Utang</span>
            </a>
            <a href="{{ route('users.index') }}"
                class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('users.*') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="text-[10px] font-medium">Anggota</span>
            </a>
            <a href="{{ route('reports.index') }}"
                class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('reports.*') || request()->routeIs('categories.*') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-medium">Laporan</span>
            </a>
        @endif
        <a href="{{ route('profile.edit') }}"
            class="flex-1 flex flex-col items-center justify-center gap-0.5 transition-all duration-300 transform {{ request()->routeIs('profile.*') ? 'text-emerald-600 scale-110' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-[10px] font-medium">Profil</span>
        </a>
    </div>
</nav>
