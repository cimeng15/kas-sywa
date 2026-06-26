@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('header_title', 'Tambah Kategori')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Kategori Baru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Buat kategori untuk pemasukan atau pengeluaran</p>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: Gaji, Belanja, Makan" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-all {{ old('type') === 'income' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                        <input type="radio" name="type" value="income" class="sr-only" {{ old('type') === 'income' ? 'checked' : '' }}>
                        <span class="text-sm font-medium {{ old('type') === 'income' ? 'text-indigo-600' : 'text-gray-700 dark:text-gray-300' }}">Pemasukan</span>
                    </label>
                    <label class="relative flex items-center justify-center px-4 py-3 border-2 rounded-lg cursor-pointer transition-all {{ old('type') === 'expense' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                        <input type="radio" name="type" value="expense" class="sr-only" {{ old('type') === 'expense' ? 'checked' : '' }}>
                        <span class="text-sm font-medium {{ old('type') === 'expense' ? 'text-indigo-600' : 'text-gray-700 dark:text-gray-300' }}">Pengeluaran</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warna -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna</label>
                <div class="mt-1 flex items-center gap-3">
                    <input type="color" name="color" id="color" value="{{ old('color', '#6366f1') }}" class="h-10 w-16 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-800 cursor-pointer p-1">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Pilih warna untuk kategori</span>
                </div>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ikon -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ikon (Emoji)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-lg" placeholder="💰" maxlength="10">
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Gunakan emoji sebagai ikon kategori, misal: 💰 🛒 🍔</p>
                @error('icon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
