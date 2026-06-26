<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $memberIds = $user->familyMemberIds();

        $query = Transaction::with(['category', 'user', 'creator'])->whereIn('user_id', $memberIds);

        if ($request->filled('month')) $query->whereMonth('date', $request->month);
        if ($request->filled('year')) $query->whereYear('date', $request->year);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);

        $transactions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();
        $categories = Category::whereIn('user_id', $memberIds)->orderBy('name')->get();
        $familyMembers = $user->isOrangTua()
            ? User::whereIn('id', $memberIds)->orderBy('name')->get()
            : collect([$user]);

        return view('transactions.index', compact('transactions', 'categories', 'familyMembers'));
    }

    public function create()
    {
        $user = auth()->user();
        $memberIds = $user->familyMemberIds();
        $categories = Category::whereIn('user_id', $memberIds)->orderBy('name')->get();
        $familyMembers = $user->isOrangTua()
            ? User::whereIn('id', $memberIds)->orderBy('name')->get()
            : collect([$user]);

        return view('transactions.create', compact('categories', 'familyMembers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        if (!in_array($validated['user_id'], $user->familyMemberIds())) abort(403);
        if ($validated['category_id']) {
            $category = Category::findOrFail($validated['category_id']);
            if (!in_array($category->user_id, $user->familyMemberIds())) abort(403);
        }

        $validated['created_by'] = $user->id;
        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = auth()->user();
        $transaction = Transaction::whereIn('user_id', $user->familyMemberIds())->findOrFail($id);
        $memberIds = $user->familyMemberIds();
        $categories = Category::whereIn('user_id', $memberIds)->orderBy('name')->get();
        $familyMembers = $user->isOrangTua()
            ? User::whereIn('id', $memberIds)->orderBy('name')->get()
            : collect([$user]);

        return view('transactions.edit', compact('transaction', 'categories', 'familyMembers'));
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $transaction = Transaction::whereIn('user_id', $user->familyMemberIds())->findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        if (!in_array($validated['user_id'], $user->familyMemberIds())) abort(403);
        if ($validated['category_id']) {
            $category = Category::findOrFail($validated['category_id']);
            if (!in_array($category->user_id, $user->familyMemberIds())) abort(403);
        }

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $transaction = Transaction::whereIn('user_id', $user->familyMemberIds())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
