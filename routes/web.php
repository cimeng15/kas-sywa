<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TelegramLinkController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transactions', TransactionController::class);

    Route::middleware(['role:orang_tua'])->group(function () {
        Route::resource('debts', DebtController::class);
        Route::post('/debts/{debt}/pay', [DebtController::class, 'pay'])->name('debts.pay');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports', [ReportController::class, 'generate'])->name('reports.generate');
        Route::resource('users', UserController::class)->except(['show']);

        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index')->name('notifications.index');
            Route::post('/notifications/{notification}/read', 'markAsRead')->name('notifications.read');
            Route::post('/notifications/read-all', 'markAllAsRead')->name('notifications.readAll');
            Route::get('/notifications/unread-count', 'unreadCount')->name('notifications.unreadCount');
        });
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar');
        Route::delete('/profile/avatar', 'destroyAvatar')->name('profile.avatar.destroy');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(TelegramLinkController::class)->group(function () {
        Route::get('/telegram/link', 'index')->name('telegram.link');
        Route::post('/telegram/link/generate-otp', 'generateOtp')->name('telegram.otp.generate');
        Route::delete('/telegram/link', 'unlink')->name('telegram.unlink');
        Route::post('/telegram/set-token', 'setToken')->name('telegram.set-token');
        Route::post('/telegram/setup-webhook', 'setupWebhook')->name('telegram.setup-webhook');
        Route::get('/telegram/webhook-info', 'checkWebhook')->name('telegram.webhook-info');
    });
});

require __DIR__.'/auth.php';
