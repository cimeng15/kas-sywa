<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TelegramLinkController extends Controller
{
    public function index()
    {
        $telegramUser = TelegramUser::where('user_id', auth()->id())
            ->whereNotNull('linked_at')
            ->first();

        $botToken = config('telegram.bot_token');
        $hasToken = !empty($botToken);
        $webhookInfo = null;

        if ($hasToken) {
            try {
                $webhookInfo = $this->getWebhookInfo();
            } catch (\Exception $e) {
                $webhookInfo = ['error' => 'Gagal menghubungi Telegram API'];
            }
        }

        return view('telegram.index', compact('telegramUser', 'hasToken', 'webhookInfo'));
    }

    public function generateOtp()
    {
        $telegramUser = TelegramUser::firstOrNew(['user_id' => auth()->id()]);

        $telegramUser->otp_code = strtoupper(Str::random(6));
        $telegramUser->otp_expires_at = now()->addMinutes(5);
        $telegramUser->save();

        return response()->json([
            'otp_code' => $telegramUser->otp_code,
            'expires_at' => $telegramUser->otp_expires_at->format('H:i:s'),
        ]);
    }

    public function unlink()
    {
        TelegramUser::where('user_id', auth()->id())->delete();

        return redirect()->route('telegram.link')
            ->with('status', 'Akun Telegram berhasil diputuskan.');
    }

    public function setToken(Request $request)
    {
        $request->validate([
            'bot_token' => 'required|string|min:40',
            'webhook_secret' => 'nullable|string|max:64',
        ]);

        $token = trim($request->bot_token);
        $secret = trim($request->webhook_secret ?? '');

        // Test token
        $response = Http::get("https://api.telegram.org/bot{$token}/getMe");
        if (!$response->ok() || !$response->json('ok')) {
            return back()->with('error', 'Token bot tidak valid. Pastikan token dari @BotFather.');
        }

        $botName = $response->json('result.username');

        $this->updateEnv('TELEGRAM_BOT_TOKEN', $token);
        $this->updateEnv('TELEGRAM_WEBHOK_SECRET', $secret);
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        $msg = "Bot @{$botName} berhasil dihubungkan!";
        if ($secret) {
            $msg .= " Secret webhook diset.";
        }
        return back()->with('status', $msg);
    }

    public function setupWebhook()
    {
        $token = config('telegram.bot_token');
        if (empty($token)) {
            return back()->with('error', 'Set token bot terlebih dahulu.');
        }

        $webhookUrl = rtrim(config('app.url'), '/') . '/telegram/webhook';
        $params = ['url' => $webhookUrl];

        $secret = config('telegram.webhook.secret_token');
        if (!empty($secret)) {
            $params['secret_token'] = $secret;
        }

        $response = Http::get("https://api.telegram.org/bot{$token}/setWebhook", $params);

        if ($response->ok() && $response->json('ok')) {
            return back()->with('status', "Webhook berhasil diaktifkan! URL: {$webhookUrl}");
        }

        return back()->with('error', 'Gagal mengaktifkan webhook: ' . $response->json('description', 'Unknown error'));
    }

    public function checkWebhook()
    {
        $info = $this->getWebhookInfo();
        return response()->json($info);
    }

    protected function getWebhookInfo(): ?array
    {
        $token = config('telegram.bot_token');
        if (empty($token)) return null;

        $response = Http::get("https://api.telegram.org/bot{$token}/getWebhookInfo");
        if (!$response->ok()) return null;

        return $response->json('result');
    }

    protected function updateEnv(string $key, string $value): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        if (str_contains($content, "{$key}=")) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $content);
    }
}
