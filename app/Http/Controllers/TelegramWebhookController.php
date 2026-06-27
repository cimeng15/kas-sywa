<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TelegramUser;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    protected function telegram(): Api
    {
        return new Api(config('telegram.bot_token'));
    }

    public function handle(Request $request): void
    {
        $update = $request->all();

        $message = $update['message'] ?? null;
        if (!$message || !isset($message['chat']['id'], $message['text'])) {
            return;
        }

        $chatId = $message['chat']['id'];
        $text = trim($message['text']);

        $linked = TelegramUser::where('telegram_id', $chatId)
            ->whereNotNull('linked_at')
            ->first();

        if (str_starts_with($text, '/bantu')) {
            $this->handleBantu($chatId);
            return;
        }

        if (preg_match('/^\/link\s+(\S+)/iu', $text, $m)) {
            $this->handleLink($chatId, trim($m[1]));
            return;
        }

        if (!$linked) {
            $this->sendMessage($chatId, "❌ Akun Telegram belum terhubung.\nBuka web Kas-Keluarga untuk mendapatkan kode OTP, lalu ketik /link \\<kode\\>");
            return;
        }

        if (preg_match('/^\/(masuk|keluar)$/iu', $text, $m)) {
            $type = strtolower($m[1]) === 'masuk' ? 'income' : 'expense';
            $linked->update(['pending_command' => $type]);
            $label = $type === 'income' ? 'pemasukan' : 'pengeluaran';
            $this->sendMessage($chatId, "Silakan ketik nominal dan deskripsi untuk {$label}.\n\n_Contoh: 500 Gaji bulan ini_");
            return;
        }

        if (preg_match('/^\/(masuk|keluar)\s+(\d+(?:[.,]\d+)?)\s+(.+)/iu', $text, $m)) {
            $type = strtolower($m[1]) === 'masuk' ? 'income' : 'expense';
            $amount = (float) str_replace(',', '.', $m[2]);
            $description = trim($m[3]);
            $this->saveTransaction($chatId, $linked, $type, $amount, $description);
            return;
        }

        if ($linked->pending_command) {
            if (preg_match('/^(\d+(?:[.,]\d+)?)\s+(.+)/iu', $text, $m)) {
                $amount = (float) str_replace(',', '.', $m[1]);
                $description = trim($m[2]);
                $type = $linked->pending_command;
                $linked->update(['pending_command' => null]);
                $this->saveTransaction($chatId, $linked, $type, $amount, $description);
                return;
            }

            $linked->update(['pending_command' => null]);
            $this->sendMessage($chatId, "❌ Format salah. Transaksi dibatalkan.\n\nKetik /masuk atau /keluar untuk mencatat transaksi baru.");
            return;
        }

        $this->sendMessage($chatId, "❌ Perintah tidak dikenal.\n\nKetik /bantu untuk melihat daftar perintah.");
    }

    protected function saveTransaction(int $chatId, TelegramUser $linked, string $type, float $amount, string $description): void
    {
        if ($amount <= 0) {
            $this->sendMessage($chatId, "❌ Nominal harus lebih dari 0. Transaksi dibatalkan.");
            return;
        }

        $user = $linked->user;
        $category = $this->detectCategory($user, $type, $description);

        Transaction::create([
            'user_id' => $user->id,
            'created_by' => $user->id,
            'category_id' => $category?->id,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'date' => now(),
        ]);

        $typeLabel = $type === 'income' ? 'Pemasukan' : 'Pengeluaran';
        $categoryName = $category ? $category->name : 'Tanpa Kategori';
        $emoji = $type === 'income' ? '💰' : '💸';
        $amountFormatted = number_format($amount, 0, ',', '.');

        $reply = "✅ *Transaksi berhasil dicatat!*\n\n"
            . "{$emoji} Jenis: *{$typeLabel}*\n"
            . "💵 Nominal: *Rp{$amountFormatted}*\n"
            . "📂 Kategori: *{$categoryName}*\n"
            . "📝 Deskripsi: {$description}\n"
            . "📅 Tanggal: " . now()->translatedFormat('d F Y H:i');

        $this->sendMessage($chatId, $reply);
    }

    protected function handleBantu(int $chatId): void
    {
        $text = "📋 *Daftar Perintah Kas-Keluarga*\n\n"
            . "/masuk \\<nominal\\> \\<deskripsi\\> — Catat pemasukan\n"
            . "_Contoh: /masuk 500 Gaji_\n\n"
            . "/keluar \\<nominal\\> \\<deskripsi\\> — Catat pengeluaran\n"
            . "_Contoh: /keluar 150 Makan siang_\n\n"
            . "Atau ketik /masuk atau /keluar saja, lalu ikuti petunjuk.\n\n"
            . "/link \\<kode\\> — Hubungkan akun Telegram\n"
            . "_Contoh: /link ABC123_\n\n"
            . "/bantu — Tampilkan daftar ini";

        $this->sendMessage($chatId, $text);
    }

    protected function handleLink(int $chatId, string $code): void
    {
        $telegramUser = TelegramUser::where('otp_code', strtoupper($code))->first();

        if (!$telegramUser || !$telegramUser->isOtpValid()) {
            $this->sendMessage($chatId, "❌ Kode OTP tidak valid atau sudah kadaluarsa.\nSilakan buka web Kas-Keluarga dan generate kode baru.");
            return;
        }

        $existingLink = TelegramUser::where('telegram_id', $chatId)
            ->whereNotNull('linked_at')
            ->first();

        if ($existingLink && $existingLink->id !== $telegramUser->id) {
            $this->sendMessage($chatId, "❌ Akun Telegram ini sudah terhubung dengan akun Kas-Keluarga lain.");
            return;
        }

        $telegramUser->update([
            'telegram_id' => $chatId,
            'chat_id' => $chatId,
            'otp_code' => null,
            'otp_expires_at' => null,
            'linked_at' => now(),
        ]);

        $userName = $telegramUser->user->name;
        $this->sendMessage($chatId, "✅ Akun Telegram berhasil dihubungkan dengan *{$userName}*!\n\nSekarang kamu bisa mencatat transaksi via chat.\nKetik /bantu untuk bantuan.");
    }

    protected function detectCategory($user, string $type, string $description): ?Category
    {
        $categories = Category::where('user_id', $user->id)
            ->where('type', $type)
            ->get();

        $desc = strtolower($description);

        foreach ($categories as $cat) {
            $words = explode(' ', strtolower($cat->name));
            foreach ($words as $word) {
                if (strlen($word) >= 3 && str_contains($desc, $word)) {
                    return $cat;
                }
            }
        }

        return $categories->first();
    }

    protected function sendMessage(int $chatId, string $text): void
    {
        try {
            $this->telegram()->sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage failed', [
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
