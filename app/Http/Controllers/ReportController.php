<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        return view('reports.index', [
            'month' => $now->month,
            'year' => $now->year,
            'data' => null,
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:' . Carbon::now()->year,
        ]);

        $month = $validated['month'];
        $year = $validated['year'];

        $transactions = auth()->user()->transactions()
            ->with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $incomeByCategory = $transactions
            ->where('type', 'income')
            ->groupBy('category_id')
            ->map(function ($items) {
                $category = $items->first()->category;
                return [
                    'category_name' => $category ? $category->name : 'Tanpa Kategori',
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->values();

        $expenseByCategory = $transactions
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->map(function ($items) {
                $category = $items->first()->category;
                return [
                    'category_name' => $category ? $category->name : 'Tanpa Kategori',
                    'total' => $items->sum('amount'),
                    'count' => $items->count(),
                ];
            })
            ->values();

        $data = [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'transactions' => $transactions,
        ];

        return view('reports.index', compact('month', 'year', 'data'));
    }
}
