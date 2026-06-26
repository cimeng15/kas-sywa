@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')
<div class="space-y-5 sm:space-y-6 page-enter">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium animate-slide-up">{{ session('success') }}</div>
    @endif

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Saldo Kas - Gradient Emerald -->
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg shadow-emerald-500/20 card-lift animate-slide-up delay-100 shine-effect">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl animate-pulse-glow"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs sm:text-sm font-semibold text-emerald-50">Saldo Kas</p>
                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-lg sm:text-2xl font-black text-white">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Pemasukan - Gradient Green -->
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-lime-500 to-green-600 shadow-lg shadow-lime-500/20 card-lift animate-slide-up delay-200 shine-effect">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl animate-pulse-glow"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs sm:text-sm font-semibold text-lime-50">Pemasukan</p>
                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                </div>
                <p class="text-lg sm:text-2xl font-black text-white">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Pengeluaran - Gradient Rose -->
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-rose-500 to-pink-600 shadow-lg shadow-rose-500/20 card-lift animate-slide-up delay-300 shine-effect">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl animate-pulse-glow"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs sm:text-sm font-semibold text-rose-50">Pengeluaran</p>
                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                    </div>
                </div>
                <p class="text-lg sm:text-2xl font-black text-white">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Total Utang - Gradient Amber -->
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/20 card-lift animate-slide-up delay-400 shine-effect">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl animate-pulse-glow"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs sm:text-sm font-semibold text-amber-50">Total Utang</p>
                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <p class="text-lg sm:text-2xl font-black text-white">Rp {{ number_format($totalUtang ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Notifications + Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        @if(auth()->user()->isOrangTua())
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden animate-slide-left delay-300">
            <div class="flex items-center justify-between px-4 sm:px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pengingat Jatuh Tempo
                </h3>
                <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($notifications as $notif)
                    <div class="px-4 sm:px-5 py-3.5 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($notif->type === 'due_date')
                                <span class="inline-flex h-8 w-8 rounded-xl bg-red-100 dark:bg-red-950/50 items-center justify-center">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                            @elseif($notif->type === 'warning')
                                <span class="inline-flex h-8 w-8 rounded-xl bg-amber-100 dark:bg-amber-950/50 items-center justify-center">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </span>
                            @else
                                <span class="inline-flex h-8 w-8 rounded-xl bg-blue-100 dark:bg-blue-950/50 items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $notif->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="inline-flex h-12 w-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 items-center justify-center mb-3">
                            <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada pengingat</p>
                    </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Transaksi Terbaru -->
        <div class="{{ auth()->user()->isOrangTua() ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden animate-slide-left delay-400">
            <div class="flex items-center justify-between px-4 sm:px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                    Transaksi Terbaru
                </h3>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua</a>
            </div>

            <!-- Desktop: Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                            @if(auth()->user()->isOrangTua())                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dinput oleh</th>@endif
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($latestTransactions as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $trx->date->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $trx->description }}</td>
                                @if(auth()->user()->isOrangTua())<td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $trx->creator->name ?? $trx->user->name ?? '-' }}</td>@endif
                                <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $trx->category->name ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    @if($trx->type === 'income')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">Pemasukan</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-sm text-right font-bold {{ $trx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ auth()->user()->isOrangTua() ? 6 : 5 }}" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile: Card List -->
            <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($latestTransactions as $trx)
                    <div class="px-4 py-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $trx->description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $trx->date->format('d M Y') }}
                                    @if(auth()->user()->isOrangTua() && $trx->creator) • Dinput: {{ $trx->creator->name }}@endif
                                    • {{ $trx->category->name ?? '-' }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold {{ $trx->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                @if($trx->type === 'income')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 mt-1">Masuk</span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400 mt-1">Keluar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada transaksi</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
