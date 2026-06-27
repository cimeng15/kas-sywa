<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramLinkController extends Controller
{
    public function index()
    {
        $telegramUser = TelegramUser::where('user_id', auth()->id())
            ->whereNotNull('linked_at')
            ->first();

        return view('telegram.index', compact('telegramUser'));
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
}
