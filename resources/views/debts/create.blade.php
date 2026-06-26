@extends('layouts.app')

@section('title', 'Tambah Utang / Piutang')
@section('header_title', 'Tambah Utang / Piutang')

@section('content')
<div class="max-w-lg mx-auto" x-data="{ 
    type: '{{ old('type', 'utang') }}',
    paymentType: '{{ old('payment_type', 'bebas') }}',
}">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Utang / Piutang Baru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Catat utang atau piutang baru</p>
        </div>

        <form action="{{ route('debts.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Tipe Utang/Piutang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="type = 'utang'"
                        :class="type === 'utang' ? 'border-red-500 bg-red-50 text-red-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all">
                        <span class="text-sm font-medium">Utang</span>
                    </button>
                    <button type="button" @click="type = 'piutang'"
                        :class="type === 'piutang' ? 'border-green-500 bg-green-50 text-green-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all">
                        <span class="text-sm font-medium">Piutang</span>
                    </button>
                </div>
                <input type="hidden" name="type" :value="type">
                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Tipe Pembayaran -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="paymentType = 'bebas'"
                        :class="paymentType === 'bebas' ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all text-center">
                        <span class="text-sm font-medium">Bebas<br><span class="text-xs font-normal opacity-70">nominal bebas</span></span>
                    </button>
                    <button type="button" @click="paymentType = 'cicilan_tetap'"
                        :class="paymentType === 'cicilan_tetap' ? 'border-indigo-600 bg-indigo-50 text-indigo-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-lg transition-all text-center">
                        <span class="text-sm font-medium">Cicilan Tetap<br><span class="text-xs font-normal opacity-70">per bulan</span></span>
                    </button>
                </div>
                <input type="hidden" name="payment_type" :value="paymentType">
                @error('payment_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nama Orang -->
            <div>
                <label for="person_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama / Keterangan Pihak</label>
                <input type="text" name="person_name" id="person_name" value="{{ old('person_name') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Contoh: Bank BCA / Budi / Dealer Motor" required>
                @error('person_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Jumlah Total -->
            <div>
                <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Total (Rp)</label>
                <x-currency-input name="total_amount" :value="old('total_amount')" required />
                @error('total_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Nominal Cicilan (hanya jika cicilan_tetap) -->
            <div x-show="paymentType === 'cicilan_tetap'" x-cloak>
                <label for="installment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal Cicilan per Bulan (Rp)</label>
                <x-currency-input name="installment_amount" :value="old('installment_amount')" />
                @error('installment_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Contoh: motor 12jt, cicilan 1jt/bulan = 12x cicilan.</p>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Jatuh Tempo</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Opsional. Untuk cicilan tetap, isi tanggal jatuh tempo pertama.</p>
                @error('due_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Catatan -->
            <div>
                <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                <textarea name="note" id="note" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Catatan tambahan...">{{ old('note') }}</textarea>
                @error('note')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('debts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>


@endsection
