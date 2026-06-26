@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header_title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    @if(session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Profil berhasil diperbarui.</div>
    @elseif(session('status') === 'avatar-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Foto profil berhasil diubah.</div>
    @elseif(session('status') === 'avatar-removed')
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Foto profil berhasil dihapus.</div>
    @endif

    <!-- Info User + Avatar -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-6">
        <div class="flex items-center gap-4">
            @if(Auth::user()->hasAvatar())
                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover flex-shrink-0">
            @else
                <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-medium flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><span class="font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->username }}</span> • {{ Auth::user()->email }}</p>
                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->role === 'orang_tua' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ Auth::user()->role === 'orang_tua' ? 'Orang Tua' : 'Anak' }}
                </span>
            </div>
        </div>

        <!-- Upload Avatar dengan Crop -->
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <x-avatar-crop />
        </div>

        <!-- Form hapus avatar (terpisah) -->
        @if(Auth::user()->hasAvatar())
            <form id="remove-avatar-form" method="POST" action="{{ route('profile.avatar.destroy') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            <div x-data="{ open: false }" x-on:confirm-remove-avatar.window="open = true" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-950/60" @click="open = false"></div>
                <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-xl max-w-sm w-full p-6 z-10" x-transition>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">Hapus Foto Profil?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Foto profil akan dihapus permanen.</p>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Batal</button>
                        <button type="button" onclick="document.getElementById('remove-avatar-form').submit()" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">Hapus</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Update Profile Information -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Profil</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', Auth::user()->username) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Ubah Password</h3>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('current_password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">Ubah Password</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Logout -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">Keluar dari Akun</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Klik tombol di bawah untuk keluar dari akun ini.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white dark:bg-gray-900 hover:bg-red-50 transition">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection
