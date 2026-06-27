@extends('layouts.app')

@section('title', 'Transaksi')
@section('header_title', 'Transaksi')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Bar & Actions -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="month" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tahun</option>
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <select name="type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                <select name="category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @if(auth()->user()->isOrangTua() && $familyMembers->count() > 1)
                <select name="user_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Anggota</option>
                    @foreach($familyMembers as $member)
                        <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                    @endforeach
                </select>
                @endif
                <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['month', 'year', 'type', 'category_id', 'user_id']))
                    <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Reset</a>
                @endif
            </form>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm whitespace-nowrap">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Transactions Table (Desktop) -->
    <div class="hidden md:block bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                        @if(auth()->user()->isOrangTua())
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dinput oleh</th>
                        @endif
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ \Carbon\Carbon::parse($trx->date)->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $trx->description }}</td>
                            @if(auth()->user()->isOrangTua())
                                <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $trx->creator->name ?? $trx->user->name ?? '-' }}</td>
                            @endif
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $trx->category->name ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @if($trx->type === 'income')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pemasukan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm text-right font-medium {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-600' }} whitespace-nowrap">
                                {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-sm text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('transactions.edit', $trx->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isOrangTua() ? 7 : 6 }}" class="px-5 py-16 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada transaksi</p>
                                <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                    Tambah Transaksi
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Transactions Cards (Mobile) -->
    <div class="md:hidden space-y-3">
        @forelse($transactions as $trx)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-4">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $trx->description }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($trx->date)->format('d M Y H:i') }}
                            @if(auth()->user()->isOrangTua() && $trx->user)
                                • {{ $trx->user->name }}
                            @endif
                        </p>
                    </div>
                    <p class="text-sm font-medium {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-600' }} whitespace-nowrap">
                        {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if($trx->type === 'income')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pemasukan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Pengeluaran</span>
                        @endif
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->category->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('transactions.edit', $trx->id) }}" class="text-xs font-medium text-indigo-600">Edit</a>
                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-12 text-center">
                <p class="text-sm text-gray-500">Belum ada transaksi</p>
                <a href="{{ route('transactions.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Tambah Transaksi
                </a>
            </div>
        @endforelse
        @if($transactions->hasPages())
            <div class="py-2">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
