@extends('layouts.app')

@section('title', 'Utang Piutang')
@section('header_title', 'Utang Piutang')

@section('content')
<div class="space-y-6" x-data="{ openPayId: null }">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <form action="{{ route('debts.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="type" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Tipe</option>
                    <option value="utang" {{ request('type') === 'utang' ? 'selected' : '' }}>Utang</option>
                    <option value="piutang" {{ request('type') === 'piutang' ? 'selected' : '' }}>Piutang</option>
                </select>
                <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Filter
                </button>
                @if(request('type') || request('status'))
                    <a href="{{ route('debts.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Reset</a>
                @endif
            </form>
            <a href="{{ route('debts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Utang / Piutang
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($debts as $debt)
            @php
                $remaining = $debt->remaining_amount;
                $progress = $debt->total_amount > 0 ? (($debt->total_amount - $remaining) / $debt->total_amount) * 100 : 0;
                $isLunas = $debt->status === 'lunas';
                $isOverdue = $debt->due_date && \Carbon\Carbon::parse($debt->due_date)->isPast() && $remaining > 0 && !$isLunas;
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-5 flex flex-col">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-10 w-10 rounded-lg {{ $debt->type === 'piutang' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 {{ $debt->type === 'piutang' ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $debt->person_name }}</h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $debt->note ?? 'Tidak ada catatan' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $debt->type === 'piutang' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $debt->type === 'piutang' ? 'Piutang' : 'Utang' }}
                        </span>
                        @if($debt->isCicilanTetap())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Cicilan</span>
                        @endif
                    </div>
                </div>

                @if($debt->isCicilanTetap())
                    <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-gray-500 dark:text-gray-400">Cicilan ke</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $debt->getPaidInstallments() }} / {{ $debt->getTotalInstallments() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-gray-400">Per bulan</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Rp {{ number_format($debt->installment_amount, 0, ',', '.') }}</span>
                        </div>
                        @php $overdue = $debt->getOverdueInstallments(); @endphp
                        @if($overdue > 0)
                            <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-red-600 font-medium">⚠ {{ $overdue }} cicilan jatuh tempo belum dibayar</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex items-end justify-between mb-3">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($debt->total_amount, 0, ',', '.') }}</p>
                    </div>
                    @if($remaining > 0 && $remaining < $debt->total_amount)
                        <div class="text-right">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Sisa</p>
                            <p class="text-lg font-bold {{ $isOverdue ? 'text-red-600' : 'text-orange-600' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
                        </div>
                    @endif
                </div>

                <div class="mb-2">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-500 dark:text-gray-400">Progress</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ round($progress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full progress-fill transition-all {{ $isLunas ? 'bg-green-500' : ($isOverdue ? 'bg-red-500' : 'bg-indigo-500') }}" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 mb-3">
                    <div class="flex items-center gap-1.5 text-xs">
                        @if($debt->due_date)
                            <span class="{{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ \Carbon\Carbon::parse($debt->due_date)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">Tanpa jatuh tempo</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $isLunas ? 'bg-green-100 text-green-800' : '' }}
                        {{ $isOverdue ? 'bg-red-100 text-red-800' : '' }}
                        {{ !$isLunas && !$isOverdue ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        {{ $isLunas ? 'Lunas' : ($isOverdue ? 'Jatuh Tempo' : 'Belum Lunas') }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-auto">
                    <a href="{{ route('debts.show', $debt->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-md text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Detail</a>
                    @if(!$isLunas && $remaining > 0)
                        <button type="button" @click="openPayId = {{ $debt->id }}"
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md text-xs font-medium text-white hover:bg-indigo-700 transition">
                            Bayar
                        </button>
                    @endif
                    <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white dark:bg-gray-900 hover:bg-red-50 transition">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-gray-900 rounded-xl shadow-sm dark:shadow-gray-950/20 border border-gray-100 dark:border-gray-800 p-16 text-center">
                <p class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada utang atau piutang</p>
                <a href="{{ route('debts.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">Tambah Utang / Piutang</a>
            </div>
        @endforelse
    </div>

    @if($debts->hasPages())
        <div class="px-5 py-4">{{ $debts->links() }}</div>
    @endif

    <!-- Payment Modals -->
    @foreach($debts as $debt)
        @php $remaining = $debt->remaining_amount; @endphp
        @if($debt->status === 'belum_lunas' && $remaining > 0)
        <div x-show="openPayId === {{ $debt->id }}" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-950/60" @click="openPayId = null"></div>
                <div class="relative bg-white dark:bg-gray-900 rounded-xl shadow-xl max-w-md w-full p-6 z-10" x-show="openPayId === {{ $debt->id }}" x-transition>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Bayar - {{ $debt->person_name }}</h3>
                        <button type="button" @click="openPayId = null" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Sisa: <span class="font-semibold text-orange-600">Rp {{ number_format($remaining, 0, ',', '.') }}</span></p>

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
                                <button type="button" @click="openPayId = null" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Batal</button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">Bayar Cicilan</button>
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
                                <button type="button" @click="setFull()" class="mt-1 text-xs text-indigo-600 hover:text-indigo-800">Isi sisa lunas (Rp {{ number_format($remaining, 0, ',', '.') }})</button>
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
                                <button type="button" @click="openPayId = null" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 transition">Batal</button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">Bayar</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>


@endsection
