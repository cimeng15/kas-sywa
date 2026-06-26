@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header_title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            @php
                $unreadCount = $notifications->where('is_read', false)->count();
            @endphp
            {{ $unreadCount }} notifikasi belum dibaca
        </p>
        @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tandai Semua Sudah Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notification List -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($notifications as $notif)
            <div class="px-5 py-4 flex items-start gap-4 {{ $notif->is_read ? 'opacity-60' : '' }}">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    @if($notif->type === 'due_date')
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @elseif($notif->type === 'warning')
                        <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    @elseif($notif->type === 'transaction')
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $notif->title }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">{{ $notif->message }}</p>
                        </div>
                        @if(!$notif->is_read)
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-indigo-600 flex-shrink-0 mt-1.5"></span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $notif->created_at->diffForHumans() }}</p>
                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800">Tandai sudah dibaca</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-16 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada notifikasi</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki notifikasi saat ini.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="px-5 py-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
