@extends('layouts.app')

@section('title', 'Kategori')
@section('header_title', 'Kategori')

@section('content')
<div class="space-y-6">
    <!-- Actions -->
    <div class="flex justify-end">
        <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    <!-- Kategori Pemasukan -->
    <div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-green-500"></span>
            Kategori Pemasukan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @php $incomeCategories = $categories->where('type', 'income'); @endphp
            @forelse($incomeCategories as $cat)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-4 hover:shadow-md dark:hover:shadow-gray-950/30 transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $cat->color ?? '#e5e7eb' }}20">
                                {{ $cat->icon ?? '💰' }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cat->name }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $cat->color ?? '#6b7280' }}"></span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $cat->color ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('categories.edit', $cat->id) }}" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-800 rounded-md transition" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-gray-800 rounded-md transition" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-900 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori pemasukan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kategori Pengeluaran -->
    <div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-red-500"></span>
            Kategori Pengeluaran
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @php $expenseCategories = $categories->where('type', 'expense'); @endphp
            @forelse($expenseCategories as $cat)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-4 hover:shadow-md dark:hover:shadow-gray-950/30 transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $cat->color ?? '#e5e7eb' }}20">
                                {{ $cat->icon ?? '💸' }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cat->name }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $cat->color ?? '#6b7280' }}"></span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $cat->color ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('categories.edit', $cat->id) }}" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-800 rounded-md transition" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-gray-800 rounded-md transition" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-900 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori pengeluaran</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
