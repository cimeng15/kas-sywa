<?php

namespace App\Services;

class TelegramCommandParser
{
    public function parse(string $text): ?array
    {
        $text = trim($text);

        if (preg_match('/^\/nota\s+(masuk|keluar)\s+(\d+(?:[.,]\d+)?)\s+(.+)/iu', $text, $m)) {
            $type = strtolower($m[1]) === 'masuk' ? 'income' : 'expense';
            $amount = (float) str_replace(',', '.', $m[2]);
            $description = trim($m[3]);

            return [
                'command' => 'nota',
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
            ];
        }

        if (preg_match('/^\/link\s+(\S+)/iu', $text, $m)) {
            return [
                'command' => 'link',
                'code' => trim($m[1]),
            ];
        }

        if (preg_match('/^\/bantu/i', $text)) {
            return ['command' => 'bantu'];
        }

        return null;
    }
}
