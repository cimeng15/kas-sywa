@extends('layouts.app')

@section('title', 'Detail Utang / Piutang')
@section('header_title', 'Detail Utang / Piutang')

@section('content')
@php
    $paidAmount = $debt->total_amount - $debt->remaining_amount;
    $remaining = $debt->remaining_amount;
    $progress = $debt->total_amount > 0 ? ($paidAmount / $debt->total_amount) * 100 : 0;
    $isLunas = $debt->status === 'lunas';
    $isOverdue = $debt->due_date && \Carbon\Carbon::parse($debt->due_date)->isPast() && $remaining > 0 && !$isLunas;
    $status = $isLunas ? 'Lunas' : ($isOverdue ? 'Jatuh Tempo' : 'Belum Lunas');
@endphp

<div class="space-y-6">
    <!-- Tombol Kembali -->
    <a href="{{ route('debts.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Utang
    </a>
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="h-14 w-14 rounded-xl {{ $debt->type === 'piutang' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                    <svg class="h-7 w-7 {{ $debt->type === 'piutang' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $debt->person_name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $debt->type === 'piutang' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $debt->type === 'piutang' ? 'Piutang' : 'Utang' }}
                        </span>
                        @if($debt->isCicilanTetap())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Cicilan Tetap</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">Pembayaran Bebas</span>
                        @endif
                    </div>
                    @if($debt->note)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $debt->note }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-4 mt-3">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($debt->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Dibayar</p>
                            <p class="text-lg font-bold text-green-600">Rp {{ number_format($paidAmount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sisa</p>
                            <p class="text-lg font-bold {{ $isOverdue ? 'text-red-600' : 'text-orange-600' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $status === 'Lunas' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $status === 'Jatuh Tempo' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $status === 'Belum Lunas' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    @if($debt->isCicilanTetap())
                        @php $overdue = $debt->getOverdueInstallments(); @endphp
                        <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Cicilan/Bulan</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($debt->installment_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Terbayar</p>
                                    <p class="text-sm font-semibold text-indigo-600">{{ $debt->getPaidInstallments() }} / {{ $debt->getTotalInstallments() }}x</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Sisa Cicilan</p>
                                    <p class="text-sm font-semibold text-orange-600">{{ $debt->getTotalInstallments() - $debt->getPaidInstallments() }}x</p>
                                </div>
                            </div>
                            @if($overdue > 0)
                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 text-center">
                                    <p class="text-xs text-red-600 font-medium">⚠ {{ $overdue }} cicilan jatuh tempo belum dibayar</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('debts.edit', $debt->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Edit</a>
                <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white dark:bg-gray-900 hover:bg-red-50 transition">Hapus</button>
                </form>
                @if($remaining > 0 && !$isLunas)
                    <button x-data="" x-on:click="$dispatch('open-modal', 'payment-modal')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                        @if($debt->isCicilanTetap()) Bayar Cicilan @else Bayar @endif
                    </button>
                @endif
            </div>
        </div>

        <div class="mt-5">
            <div class="flex items-center justify-between text-sm mb-1.5">
                <span class="text-gray-600 dark:text-gray-300">Progress Pembayaran</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ round($progress) }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                <div class="h-3 rounded-full transition-all {{ $isLunas ? 'bg-green-500' : ($isOverdue ? 'bg-red-500' : 'bg-indigo-500') }}" style="width: {{ $progress }}%"></div>
            </div>
            @if($debt->due_date)
                <p class="mt-2 text-xs {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-400 dark:text-gray-500' }}">
                    Jatuh tempo: {{ \Carbon\Carbon::parse($debt->due_date)->format('d M Y') }}
                    @if($isOverdue) (Terlambat {{ \Carbon\Carbon::parse($debt->due_date)->diffInDays(now()) }} hari) @endif
                </p>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Riwayat Pembayaran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        @if($debt->isCicilanTetap())<th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cicilan Ke</th>@endif
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($debt->payments()->latest()->get() as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            @if($debt->isCicilanTetap())<td class="px-5 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $payment->installment_number ? 'Ke-' . $payment->installment_number : '-' }}</td>@endif
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ \Carbon\Carbon::parse($payment->date)->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-green-600 whitespace-nowrap">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $payment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $debt->isCicilanTetap() ? 4 : 3 }}" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<x-modal name="payment-modal" maxWidth="md">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">@if($debt->isCicilanTetap()) Bayar Cicilan @else Bayar Utang @endif - {{ $debt->person_name }}</h3>

        @if($debt->isCicilanTetap())
            @php
                $totalInstallments = $debt->getTotalInstallments();
                $paidNumbers = $debt->getPaidInstallmentNumbers();
                $expected = $debt->getExpectedInstallments();
            @endphp
            <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bayar Cicilan Ke</label>
                    <select name="installment_number" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        @for($i = 1; $i <= $totalInstallments; $i++)
                            @php
                                $isPaid = in_array($i, $paidNumbers);
                                $isExpected = $i <= $expected;
                                $label = 'Cicilan ke-' . $i;
                                if ($isPaid) { $label .= ' (sudah dibayar)'; }
                                elseif ($isExpected) { $label .= ' (jatuh tempo)'; }
                            @endphp
                            <option value="{{ $i }}" {{ $isPaid ? 'disabled' : '' }}>{{ $label }}</option>
                        @endfor
                    </select>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Nominal: Rp {{ number_format($debt->installment_amount, 0, ',', '.') }} (otomatis)</p>
                    @if($expected > $debt->getPaidInstallments())
                        <p class="mt-1 text-xs text-red-500">⚠ {{ $expected - $debt->getPaidInstallments() }} cicilan jatuh tempo belum dibayar</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pembayaran</label>
                    <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                    <textarea name="note" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Opsional..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'payment-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 shadow-sm">Bayar Cicilan</button>
                </div>
            </form>
        @else
            <form action="{{ route('debts.pay', $debt->id) }}" method="POST" class="space-y-4">
                @csrf
                <div x-data="{ payComp: null, remaining: {{ $remaining }}, formatVal(v) { let d = (v||'').toString().replace(/\D/g,''); return d ? parseInt(d).toLocaleString('id-ID') : ''; }, setFull() { this.$refs.payDisplay.value = this.formatVal(this.remaining); this.payComp = this.remaining.toString(); } }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Pembayaran (Rp)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                        </div>
                        <input type="text" x-ref="payDisplay" @input="let d = $event.target.value.replace(/\D/g,''); $event.target.value = d ? parseInt(d).toLocaleString('id-ID') : ''; payComp = d;" inputmode="numeric" class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0" required>
                    </div>
                    <input type="hidden" name="amount" :value="payComp">
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Sisa: Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                    <button type="button" @click="setFull()" class="mt-1 text-xs text-indigo-600 hover:text-indigo-800">Isi sisa lunas</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pembayaran</label>
                    <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                    <textarea name="note" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Opsional..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'payment-modal')" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 shadow-sm">Bayar</button>
                </div>
            </form>
        @endif
    </div>
</x-modal>
@endsection
