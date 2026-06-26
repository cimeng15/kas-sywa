@extends('layouts.app')

@section('title', 'Laporan')
@section('header_title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-5">
        <form action="{{ route('reports.generate') }}" method="POST" class="flex flex-wrap items-end gap-4">
            @csrf
            <div>
                <label for="report_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                <select name="month" id="report_month" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ ($month ?? now()->month) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="report_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                <select name="year" id="report_year" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach(range(now()->year - 5, now()->year) as $y)
                        <option value="{{ $y }}" {{ ($year ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Tampilkan Laporan
            </button>
        </form>
    </div>

    @if($data)
        @php
            $totalIncome = $data['totalIncome'];
            $totalExpense = $data['totalExpense'];
            $balance = $data['balance'];
            $incomeByCategory = $data['incomeByCategory'];
            $expenseByCategory = $data['expenseByCategory'];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                        <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg {{ $balance >= 0 ? 'bg-indigo-100' : 'bg-orange-100' }} flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 {{ $balance >= 0 ? 'text-indigo-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Selisih</p>
                        <p class="text-lg font-bold {{ $balance >= 0 ? 'text-indigo-600' : 'text-orange-600' }}">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($incomeByCategory->isNotEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Pemasukan per Kategori</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Transaksi</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($incomeByCategory as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['category_name'] }}</td>
                                <td class="px-5 py-3 text-sm text-center text-gray-600 dark:text-gray-300">{{ $item['count'] }} transaksi</td>
                                <td class="px-5 py-3 text-sm text-right font-medium text-green-600">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($expenseByCategory->isNotEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Pengeluaran per Kategori</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Transaksi</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($expenseByCategory as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['category_name'] }}</td>
                                <td class="px-5 py-3 text-sm text-center text-gray-600 dark:text-gray-300">{{ $item['count'] }} transaksi</td>
                                <td class="px-5 py-3 text-sm text-right font-medium text-red-600">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($incomeByCategory->isEmpty() && $expenseByCategory->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-16 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada transaksi untuk periode ini.</p>
            </div>
        @endif
    @endif
</div>
@endsection
