<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Notification;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        $memberIds = $user->familyMemberIds();

        $transactionsQuery = Transaction::whereIn('user_id', $memberIds);
        $totalIncome = (clone $transactionsQuery)->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalExpense = (clone $transactionsQuery)->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $totalUtang = Debt::whereIn('user_id', $memberIds)
            ->where('type', 'utang')->where('status', 'belum_lunas')->sum('remaining_amount');
        $totalPiutang = Debt::whereIn('user_id', $memberIds)
            ->where('type', 'piutang')->where('status', 'belum_lunas')->sum('remaining_amount');

        $latestTransactions = Transaction::with(['category', 'user'])
            ->whereIn('user_id', $memberIds)
            ->latest('date')->take(5)->get();

        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)->latest()->take(5)->get();

        $familyMembers = $user->isOrangTua()
            ? $user->children()->orderBy('name')->get()
            : collect();

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'balance',
            'totalUtang', 'totalPiutang',
            'latestTransactions', 'notifications', 'familyMembers'
        ));
    }
}
