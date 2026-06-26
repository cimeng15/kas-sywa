<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    private function getFamilyDebts()
    {
        $memberIds = auth()->user()->familyMemberIds();
        return Debt::whereIn('user_id', $memberIds);
    }

    public function index(Request $request)
    {
        $query = $this->getFamilyDebts()->withCount('payments');

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);

        $debts = $query->latest()->paginate(15)->withQueryString();

        return view('debts.index', compact('debts'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:utang,piutang',
            'payment_type' => 'required|in:bebas,cicilan_tetap',
            'person_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type === 'cicilan_tetap') {
            $rules['installment_amount'] = 'required|numeric|min:1';
        }

        $validated = $request->validate($rules);

        $validated['user_id'] = auth()->id();
        $validated['created_by'] = auth()->id();
        $validated['remaining_amount'] = $validated['total_amount'];
        $validated['status'] = 'belum_lunas';

        if ($validated['payment_type'] === 'bebas') {
            $validated['installment_amount'] = null;
        }

        Debt::create($validated);

        return redirect()->route('debts.index')->with('success', 'Utang/Piutang berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $debt = $this->getFamilyDebts()->findOrFail($id);
        $payments = $debt->payments()->latest()->paginate(15);

        return view('debts.show', compact('debt', 'payments'));
    }

    public function edit(string $id)
    {
        $debt = $this->getFamilyDebts()->findOrFail($id);
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, string $id)
    {
        $debt = $this->getFamilyDebts()->findOrFail($id);

        $rules = [
            'type' => 'required|in:utang,piutang',
            'payment_type' => 'required|in:bebas,cicilan_tetap',
            'person_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type === 'cicilan_tetap') {
            $rules['installment_amount'] = 'required|numeric|min:1';
        }

        $validated = $request->validate($rules);

        if ($validated['payment_type'] === 'bebas') {
            $validated['installment_amount'] = null;
        }

        $debt->update($validated);

        return redirect()->route('debts.index')->with('success', 'Utang/Piutang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $debt = $this->getFamilyDebts()->findOrFail($id);
        $debt->payments()->delete();
        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Utang/Piutang berhasil dihapus.');
    }

    public function pay(Request $request, string $id)
    {
        $debt = $this->getFamilyDebts()->findOrFail($id);

        if ($debt->isCicilanTetap()) {
            return $this->payInstallment($request, $debt);
        }

        return $this->payBebas($request, $debt);
    }

    private function payBebas(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $debt->remaining_amount,
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $transactionType = $debt->type === 'utang' ? 'expense' : 'income';

        $transaction = auth()->user()->transactions()->create([
            'type' => $transactionType,
            'amount' => $validated['amount'],
            'description' => 'Pembayaran ' . ($debt->type === 'utang' ? 'utang' : 'piutang') . ' - ' . $debt->person_name,
            'date' => $validated['date'],
            'created_by' => auth()->id(),
        ]);

        DebtPayment::create([
            'debt_id' => $debt->id,
            'transaction_id' => $transaction->id,
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'note' => $validated['note'] ?? null,
        ]);

        $debt->remaining_amount = max(0, $debt->remaining_amount - $validated['amount']);
        if ($debt->remaining_amount <= 0) {
            $debt->status = 'lunas';
        }
        $debt->save();

        return redirect()->route('debts.show', $debt->id)->with('success', 'Pembayaran berhasil dicatat.');
    }

    private function payInstallment(Request $request, Debt $debt)
    {
        $totalInstallments = $debt->getTotalInstallments();

        $validated = $request->validate([
            'installment_number' => 'required|integer|min:1|max:' . $totalInstallments,
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $existing = $debt->payments()->where('installment_number', $validated['installment_number'])->exists();
        if ($existing) {
            return redirect()->back()->with('error', 'Cicilan ke-' . $validated['installment_number'] . ' sudah dibayar.');
        }

        $amount = (float) $debt->installment_amount;
        $remaining = (float) $debt->remaining_amount;
        if ($amount > $remaining) {
            $amount = $remaining;
        }

        $transactionType = $debt->type === 'utang' ? 'expense' : 'income';

        $transaction = auth()->user()->transactions()->create([
            'type' => $transactionType,
            'amount' => $amount,
            'description' => 'Cicilan ke-' . $validated['installment_number'] . ' - ' . $debt->person_name,
            'date' => $validated['date'],
            'created_by' => auth()->id(),
        ]);

        DebtPayment::create([
            'debt_id' => $debt->id,
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'installment_number' => $validated['installment_number'],
            'date' => $validated['date'],
            'note' => $validated['note'] ?? null,
        ]);

        $debt->remaining_amount = max(0, $remaining - $amount);
        if ($debt->remaining_amount <= 0) {
            $debt->status = 'lunas';
        }
        $debt->save();

        return redirect()->route('debts.show', $debt->id)->with('success', 'Cicilan ke-' . $validated['installment_number'] . ' berhasil dibayar.');
    }
}
