@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('header_title', 'Edit Anggota')

@section('content')
<div class="max-w-lg mx-auto" x-data="{ role: '{{ old('role', $user->role) }}' }">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Edit Anggota</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data anggota keluarga</p>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="block w-full px-4 py-2.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" required>
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="block w-full px-4 py-2.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" required>
                @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="block w-full px-4 py-2.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" required>
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                <input type="password" name="password" id="password" class="block w-full px-4 py-2.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" placeholder="Kosongkan jika tidak ingin ganti">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full px-4 py-2.5 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" placeholder="Kosongkan jika tidak ingin ganti">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="role = 'orang_tua'"
                        :class="role === 'orang_tua' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-xl transition-all font-medium text-sm">
                        Orang Tua
                    </button>
                    <button type="button" @click="role = 'anak'"
                        :class="role === 'anak' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                        class="flex items-center justify-center px-4 py-3 border-2 rounded-xl transition-all font-medium text-sm">
                        Anak
                    </button>
                </div>
                <input type="hidden" name="role" :value="role">
                @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Batal</a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 border border-transparent rounded-xl text-sm font-bold text-white hover:from-emerald-600 hover:to-teal-700 transition shadow-lg shadow-emerald-500/20">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
