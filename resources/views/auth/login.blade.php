@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex relative overflow-hidden">
    <!-- Background animated gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 animate-gradient"></div>
    
    <!-- Floating blur orbs -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-lime-300/30 rounded-full blur-[120px] -translate-y-1/3 translate-x-1/4 animate-float-slow"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-300/20 rounded-full blur-[100px] translate-y-1/3 animate-float"></div>
    <div class="absolute top-1/2 left-1/3 w-[300px] h-[300px] bg-teal-200/20 rounded-full blur-[80px] animate-pulse-glow"></div>

    <!-- Animated wave decoration -->
    <svg class="absolute bottom-0 left-0 right-0 w-full" viewBox="0 0 1440 200" fill="none" preserveAspectRatio="none" style="height: 200px;">
        <path d="M0,100 C320,180 720,20 1440,100 L1440,200 L0,200 Z" fill="rgba(255,255,255,0.05)"/>
        <path d="M0,140 C480,80 960,200 1440,120 L1440,200 L0,200 Z" fill="rgba(255,255,255,0.08)"/>
    </svg>

    <!-- Left: Branding -->
    <div class="hidden lg:flex lg:w-1/2 relative z-10 items-center justify-center px-12">
        <div class="max-w-lg text-white">
            <div class="flex items-center gap-3 mb-8 animate-slide-left delay-100">
                <div class="h-14 w-14 rounded-2xl overflow-hidden shadow-xl animate-float ring-2 ring-white/30">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo" class="h-full w-full">
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight">Kas-Keluarga <span class="text-lime-200 text-lg font-semibold">by Sywa</span></h1>
                    <p class="text-sm text-emerald-100 font-medium">Smart Family Finance</p>
                </div>
            </div>

            <h2 class="text-5xl font-black leading-tight mb-4 animate-slide-up delay-200">
                Kelola uang<br>
                <span class="shimmer-text">keluarga</span> dengan<br>
                lebih bijak.
            </h2>
            <p class="text-lg text-emerald-50/90 leading-relaxed mb-10 animate-slide-up delay-300">
                Catat pemasukan, pantau pengeluaran, atur cicilan, dan ingat utang piutang — semua dalam satu tempat.
            </p>

            <div class="grid grid-cols-3 gap-3 animate-slide-up delay-400">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-5 border border-white/20 shadow-lg card-lift">
                    <div class="text-3xl font-black text-lime-200 mb-1">+</div>
                    <div class="text-sm font-semibold text-white">Pemasukan</div>
                    <div class="text-xs text-emerald-100/80 mt-0.5">tercatat rapi</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-5 border border-white/20 shadow-lg card-lift" style="animation-delay: 0.1s">
                    <div class="text-3xl font-black text-orange-200 mb-1">−</div>
                    <div class="text-sm font-semibold text-white">Pengeluaran</div>
                    <div class="text-xs text-emerald-100/80 mt-0.5">terkontrol</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-5 border border-white/20 shadow-lg card-lift" style="animation-delay: 0.2s">
                    <div class="text-3xl font-black text-yellow-200 mb-1">=</div>
                    <div class="text-sm font-semibold text-white">Saldo</div>
                    <div class="text-xs text-emerald-100/80 mt-0.5">jelas</div>
                </div>
            </div>

            <div class="flex items-center gap-6 mt-10 text-emerald-100/80 text-sm animate-slide-up delay-500">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-lime-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Multi-user</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-lime-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Cicilan otomatis</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-lime-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Dark mode</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12 relative z-10">
        <div class="w-full max-w-md">
            <!-- Mobile logo -->
            <div class="lg:hidden text-center mb-8 animate-bounce-in">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl overflow-hidden shadow-xl mb-4 animate-float ring-2 ring-white/30">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo" class="h-full w-full">
                </div>
                <h2 class="text-2xl font-black text-white">Kas-Keluarga <span class="text-lime-200 text-sm font-semibold">by Sywa</span></h2>
                <p class="text-sm text-emerald-100 mt-1">Masuk ke akun Anda</p>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 border border-white/40 dark:border-gray-800 animate-scale-in delay-200">
                <div class="hidden lg:block text-center mb-8">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white">Selamat Datang</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masuk untuk mengelola keuangan keluarga</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="animate-slide-up delay-300">
                        <label for="login" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Username atau Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-transform group-focus-within:scale-110">
                                <svg class="h-5 w-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <x-text-input id="login" class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-teal-500 focus:ring-teal-500 text-sm transition-all" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="username atau email" />
                        </div>
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>

                    <div class="mt-5 animate-slide-up delay-400">
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-transform group-focus-within:scale-110">
                                <svg class="h-5 w-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <x-text-input id="password" class="block w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-xl focus:border-teal-500 focus:ring-teal-500 text-sm transition-all" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center mt-5 animate-slide-up delay-500">
                        <label for="remember_me" class="flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-teal-600 shadow-sm focus:ring-teal-500 dark:bg-gray-800" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                        </label>
                    </div>

                    <div class="mt-6 animate-slide-up delay-500">
                        <button type="submit" class="btn-ripple shine-effect w-full flex items-center justify-center px-4 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-xs text-emerald-50/70 mt-6 animate-slide-up delay-700">
                &copy; {{ date('Y') }} Kas-Keluarga by Sywa
            </p>
        </div>
    </div>
</div>
@endsection
