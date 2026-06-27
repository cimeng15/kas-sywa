<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramUser extends Model
{
    protected $fillable = [
        'user_id',
        'telegram_id',
        'chat_id',
        'otp_code',
        'otp_expires_at',
        'linked_at',
        'pending_command',
    ];

    protected function casts(): array
    {
        return [
            'otp_expires_at' => 'datetime',
            'linked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOtpValid(): bool
    {
        return $this->otp_code !== null
            && $this->otp_expires_at !== null
            && $this->otp_expires_at->isFuture();
    }
}
