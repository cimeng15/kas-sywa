@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('header_title', 'Edit Transaksi')

@section('content')
<div class="max-w-lg mx-auto" x-data="{ type: '{{ old('type', $transaction->type) }}' }">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Edit Transaksi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data transaksi</p>
        </div>

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if(auth()->user()->isOrangTua() && $familyMembers->count() > 1)
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Atas Nama</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach($familyMembers as $member)
                        <option value="{{ $member->id }}" {{ old('user_id', $transaction->user_id) == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}{{ $member->id === auth()->id() ? ' (Saya)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @else
                <input type="hidden" name="user_id" value="{{ $transaction->user_id }}">
            @endif

            <!-- Tipe Transaksi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="type = 'income'"
                        :class="type === 'income' ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-medium">Pemasukan</span>
                    </button>
                    <button type="button" @click="type = 'expense'"
                        :class="type === 'expense' ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="text-sm font-medium">Pengeluaran</span>
                    </button>
                </div>
                <input type="hidden" name="type" :value="type">
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon ?? '' }} {{ $cat->name }} ({{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah (Rp)</label>
                <x-currency-input name="amount" :value="old('amount', $transaction->amount)" required />
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <input type="text" name="description" id="description" value="{{ old('description', $transaction->description) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: Belanja bulanan" required>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ old('date', \Carbon\Carbon::parse($transaction->date)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
