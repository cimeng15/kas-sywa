<?php

return [
    'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),

    'webhook' => [
        'secret_token' => env('TELEGRAM_WEBHOOK_SECRET', ''),
    ],
];
