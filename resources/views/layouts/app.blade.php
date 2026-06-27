<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeHandler()" x-init="init()" :class="{ 'dark': isDark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Kas-Keluarga by Sywa')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak]{display:none!important}</style>
        <script>
            // Anti-glitch: apply dark mode before page renders
            (function() {
                var theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200" style="font-family: 'Inter', sans-serif;">
        <div x-data="{ sidebarOpen: false }" class="lg:grid lg:grid-cols-[256px_1fr]">
            <div x-show="sidebarOpen" x-on:click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 bg-gray-900/50 hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <aside class="hidden lg:flex flex-col bg-gradient-to-b from-gray-900 via-gray-900 to-emerald-950 border-r border-white/5 h-screen sticky top-0">
                @include('layouts.navigation')
            </aside>

            <!-- Main Content -->
            <div class="flex flex-col min-h-screen">
                <header class="sticky top-0 z-20 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800 transition-colors duration-200">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div class="lg:hidden h-9 w-9 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2l3-9 4 18 3-12 2 3h4"/>
                                </svg>
                            </div>
                            <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">@yield('header_title', 'Dashboard')</h1>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-3">
                            <button @click="toggle()" class="inline-flex items-center p-2 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition" title="Toggle dark mode">
                                <svg x-show="!isDark" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="isDark" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </button>

                            @if(auth()->user()->isOrangTua())
                                <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center p-2 text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if(($unreadCount ?? 0) > 0)
                                        <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-gradient-to-r from-red-500 to-orange-500 rounded-full">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            @endif

                            <div x-data="{ open: false }" class="relative hidden lg:block">
                                <button x-on:click="open = !open" class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                                    @if(Auth::user()->hasAvatar())
                                        <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover ring-2 ring-emerald-400/30">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-on:click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden" style="display: none;">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Profil Saya</a>
                                    @if(auth()->user()->isOrangTua())
                                        <a href="{{ route('users.index') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Pengaturan Anggota</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6 lg:p-8 pb-24 lg:pb-8 page-enter">
                    @yield('content')
                </main>
            </div>
        </div>

        @include('layouts.bottom-nav')

        <script>
        function themeHandler() {
            return {
                isDark: document.documentElement.classList.contains('dark'),
                init() {
                    this.apply();
                },
                toggle() { this.isDark = !this.isDark; this.apply(); },
                apply() {
                    if (this.isDark) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); }
                    else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); }
                }
            };
        }
        </script>
        @stack('scripts')
    </body>
</html>
