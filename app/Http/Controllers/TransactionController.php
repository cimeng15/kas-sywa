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
        $ctx = $this->getFamilyContext();

        $query = Transaction::with(['category', 'user', 'creator'])->whereIn('user_id', $ctx['memberIds']);

        if ($request->filled('month')) $query->whereMonth('date', $request->month);
        if ($request->filled('year')) $query->whereYear('date', $request->year);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);

        $transactions = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'categories' => $ctx['categories'],
            'familyMembers' => $ctx['familyMembers'],
        ]);
    }

    public function create()
    {
        $ctx = $this->getFamilyContext();
        return view('transactions.create', [
            'categories' => $ctx['categories'],
            'familyMembers' => $ctx['familyMembers'],
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate($this->validationRules());
        $this->authorizeFamilyAccess($validated);

        $validated['created_by'] = $user->id;
        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $ctx = $this->getFamilyContext();
        $transaction = Transaction::whereIn('user_id', $ctx['memberIds'])->findOrFail($id);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => $ctx['categories'],
            'familyMembers' => $ctx['familyMembers'],
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $transaction = Transaction::whereIn('user_id', $user->familyMemberIds())->findOrFail($id);

        $validated = $request->validate($this->validationRules());
        $this->authorizeFamilyAccess($validated);

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

    protected function getFamilyContext(): array
    {
        $user = auth()->user();
        $memberIds = $user->familyMemberIds();

        return [
            'memberIds' => $memberIds,
            'categories' => Category::whereIn('user_id', $memberIds)->orderBy('name')->get(),
            'familyMembers' => $user->isOrangTua()
                ? User::whereIn('id', $memberIds)->orderBy('name')->get()
                : collect([$user]),
        ];
    }

    protected function validationRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ];
    }

    protected function authorizeFamilyAccess(array $validated): void
    {
        $user = auth()->user();
        $memberIds = $user->familyMemberIds();

        if (!in_array($validated['user_id'], $memberIds)) abort(403);
        if ($validated['category_id']) {
            $category = Category::findOrFail($validated['category_id']);
            if (!in_array($category->user_id, $memberIds)) abort(403);
        }
    }
}
