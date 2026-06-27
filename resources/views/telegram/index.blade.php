@extends('layouts.app')

@section('title', 'Telegram Bot')
@section('header_title', 'Telegram Bot')

@section('content')
<div class="py-8">
    <div class="mx-auto max-w-2xl space-y-6">
        @if (session('status'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-200">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Step 1: Token Bot -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center h-7 w-7 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">1</span>
                <h3 class="text-lg font-semibold text-gray-800">Token Bot Telegram</h3>
            </div>

            @if($hasToken)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4 flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm text-emerald-700">Token bot sudah diset. Bot siap digunakan.</span>
                </div>
            @else
                <p class="text-sm text-gray-600 mb-3">
                    Dapatkan token dari <strong>@BotFather</strong> di Telegram:
                    <span class="text-gray-400">chat /newbot → isi nama → simpan token</span>
                </p>
            @endif

            <form method="POST" action="{{ route('telegram.set-token') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Token Bot</label>
                    <input type="text" name="bot_token"
                        placeholder="123456789:ABCdefGHIjklMNOpqrs..."
                        class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                        value="{{ old('bot_token') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="webhook_secret"
                        placeholder="Masukkan string rahasia..."
                        class="w-full rounded-lg border-gray-300 text-sm px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500"
                        value="{{ old('webhook_secret', config('telegram.webhook.secret_token')) }}">
                    <p class="text-xs text-gray-400 mt-1">String rahasia untuk verifikasi request dari Telegram. Kosongkan jika tidak perlu.</p>
                </div>
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    {{ $hasToken ? 'Update' : 'Simpan' }}
                </button>
            </form>
        </div>

        <!-- Step 2: Setup Webhook -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center h-7 w-7 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">2</span>
                <h3 class="text-lg font-semibold text-gray-800">Aktifkan Webhook</h3>
            </div>

            <p class="text-sm text-gray-600 mb-3">Daftarkan URL aplikasi ini ke Telegram agar bot bisa menerima chat.</p>

            @if($webhookInfo && !isset($webhookInfo['error']))
                <div class="rounded-xl p-4 mb-4 {{ $webhookInfo['url'] ? 'bg-emerald-50 border border-emerald-200' : 'bg-amber-50 border border-amber-200' }}">
                    <div class="text-sm font-medium {{ $webhookInfo['url'] ? 'text-emerald-700' : 'text-amber-700' }} mb-2">
                        {{ $webhookInfo['url'] ? 'Webhook Aktif' : 'Webhook Belum Aktif' }}
                    </div>
                    @if($webhookInfo['url'])
                        <code class="text-xs text-gray-600 break-all">{{ $webhookInfo['url'] }}</code>
                    @endif
                    @if(isset($webhookInfo['last_error_date']))
                        <p class="text-xs text-red-600 mt-1">Error: {{ $webhookInfo['last_error_message'] ?? 'Unknown' }}</p>
                    @endif
                    @if(isset($webhookInfo['pending_update_count']))
                        <p class="text-xs text-gray-500 mt-1">Pending: {{ $webhookInfo['pending_update_count'] }}</p>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('telegram.setup-webhook') }}">
                @csrf
                <button type="submit" {{ !$hasToken ? 'disabled' : '' }}
                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Aktifkan Webhook
                </button>
                @if(!$hasToken)
                    <span class="text-xs text-gray-400 ml-2">Set token dulu di atas</span>
                @endif
            </form>
        </div>

        <!-- Step 3: Link Akun -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center h-7 w-7 rounded-full bg-blue-100 text-blue-700 text-sm font-bold">3</span>
                <h3 class="text-lg font-semibold text-gray-800">Hubungkan Akun</h3>
            </div>

            @if($telegramUser)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-emerald-700 font-semibold mb-1">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Akun Telegram Terhubung
                    </div>
                    <p class="text-sm text-emerald-600">Terhubung sejak {{ $telegramUser->linked_at->translatedFormat('d F Y H:i') }}</p>
                </div>

                <form method="POST" action="{{ route('telegram.unlink') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Yakin ingin memutuskan koneksi Telegram?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-100 border border-red-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Putuskan Koneksi
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-600 mb-4">
                    Generate kode OTP, lalu kirim ke bot Telegram untuk menghubungkan akun.
                </p>

                <button id="generateOtpBtn"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Generate Kode OTP
                </button>

                <div id="otpResult" class="hidden mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-sm text-blue-600 mb-2">Kode OTP (berlaku 5 menit):</p>
                    <div class="flex items-center gap-3">
                        <code id="otpCode" class="text-2xl font-mono font-bold tracking-[0.2em] text-blue-700 bg-white px-3 py-1.5 rounded-lg border border-blue-200"></code>
                        <button onclick="copyOtp()" class="text-blue-500 hover:text-blue-700 text-sm">Salin</button>
                    </div>
                    <p class="text-xs text-blue-500 mt-2">Kedaluwarsa pukul <span id="otpExpires"></span></p>
                    <p class="text-sm text-blue-600 mt-3">
                        Chat bot: <code class="bg-white px-1 rounded text-blue-700 font-mono">/link <span id="otpCode2"></span></code>
                    </p>
                </div>
            @endif
        </div>

        <!-- Perintah -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Perintah Chat Bot</h3>
            <div class="space-y-2 text-sm">
                <div class="flex gap-2">
                    <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono flex-shrink-0">/nota masuk 500 Gaji</code>
                    <span class="text-gray-500">Catat pemasukan</span>
                </div>
                <div class="flex gap-2">
                    <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono flex-shrink-0">/nota keluar 150 Makan</code>
                    <span class="text-gray-500">Catat pengeluaran</span>
                </div>
                <div class="flex gap-2">
                    <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono flex-shrink-0">/link ABC123</code>
                    <span class="text-gray-500">Hubungkan akun</span>
                </div>
                <div class="flex gap-2">
                    <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono flex-shrink-0">/bantu</code>
                    <span class="text-gray-500">Daftar perintah</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('generateOtpBtn')?.addEventListener('click', async () => {
        const res = await fetch('{{ route("telegram.otp.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
        const data = await res.json();
        document.getElementById('otpCode').textContent = data.otp_code;
        document.getElementById('otpCode2').textContent = data.otp_code;
        document.getElementById('otpExpires').textContent = data.expires_at;
        document.getElementById('otpResult').classList.remove('hidden');
    });

    function copyOtp() {
        const code = document.getElementById('otpCode').textContent;
        navigator.clipboard.writeText(code);
        alert('Kode OTP disalin!');
    }
</script>
@endsection
