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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-4">
                    <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.46-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.911.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.062 3.345-.479.329-.913.489-1.302.481-.428-.009-1.252-.242-1.865-.441-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.141.121.098.154.231.17.341.016.109.036.306.02.472z"/></svg>
                    Hubungkan Telegram
                </h3>

                @if($telegramUser)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 mb-5">
                        <div class="flex items-center gap-2 text-emerald-700 font-semibold mb-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Akun Telegram Terhubung
                        </div>
                        <p class="text-sm text-emerald-600">Terhubung sejak {{ $telegramUser->linked_at->translatedFormat('d F Y H:i') }}</p>
                    </div>

                    <form method="POST" action="{{ route('telegram.unlink') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100 border border-red-200 transition-colors"
                            onclick="return confirm('Yakin ingin memutuskan koneksi Telegram?')">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Putuskan Koneksi
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-600 mb-4">
                        Hubungkan akun Telegram kamu untuk mencatat transaksi langsung dari chat. Klik tombol di bawah untuk mendapatkan kode OTP, lalu kirimkan ke bot Telegram.
                    </p>

                    <button id="generateOtpBtn"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Generate Kode OTP
                    </button>

                    <div id="otpResult" class="hidden mt-5 bg-blue-50 border border-blue-200 rounded-xl p-5">
                        <p class="text-sm text-blue-600 mb-2">Kode OTP kamu (berlaku 5 menit):</p>
                        <div class="flex items-center gap-3">
                            <code id="otpCode" class="text-3xl font-mono font-bold tracking-[0.3em] text-blue-700 bg-white px-4 py-2 rounded-lg border border-blue-200"></code>
                            <button onclick="copyOtp()" class="text-blue-500 hover:text-blue-700 text-sm">Salin</button>
                        </div>
                        <p class="text-xs text-blue-500 mt-2">Kedaluwarsa pukul <span id="otpExpires"></span></p>

                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <p class="text-sm text-blue-600 font-medium mb-1">Cara menghubungkan:</p>
                            <ol class="text-sm text-blue-600 list-decimal list-inside space-y-1">
                                <li>Buka bot Telegram kamu</li>
                                <li>Ketik: <code class="bg-white px-1 rounded text-blue-700 font-mono">/link <span id="otpCode2"></span></code></li>
                            </ol>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Perintah yang Tersedia</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono">/nota masuk &lt;nominal&gt; &lt;deskripsi&gt;</code>
                        <p class="text-gray-500 mt-1 ml-1">Catat transaksi pemasukan</p>
                    </div>
                    <div>
                        <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono">/nota keluar &lt;nominal&gt; &lt;deskripsi&gt;</code>
                        <p class="text-gray-500 mt-1 ml-1">Catat transaksi pengeluaran</p>
                    </div>
                    <div>
                        <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono">/link &lt;kode&gt;</code>
                        <p class="text-gray-500 mt-1 ml-1">Hubungkan akun Telegram</p>
                    </div>
                    <div>
                        <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-mono">/bantu</code>
                        <p class="text-gray-500 mt-1 ml-1">Tampilkan daftar perintah</p>
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
