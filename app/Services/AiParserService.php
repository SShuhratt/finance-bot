<?php

namespace App\Services;

class AiParserService
{
    public function parse(string $text): array
    {
        $text = strtolower($text);
        $result = [
            'type' => 'expense',
            'amount' => 0,
            'category' => 'General',
            'note' => $text,
            'currency' => 'UZS',
        ];

        // Basic intent detection
        if (str_contains($text, 'salary') || str_contains($text, 'received') || str_contains($text, 'income')) {
            $result['type'] = 'income';
        } elseif (str_contains($text, 'borrowed') || str_contains($text, 'debt') || str_contains($text, 'loan')) {
            $result['type'] = 'debt';
        }

        // Extract amount and currency
        if (preg_match('/(\d+(?:\.\d+)?)\s*(m|k|usd|uzs)?/i', $text, $matches)) {
            $amount = (float)$matches[1];
            $unit = strtolower($matches[2] ?? '');

            if ($unit === 'm') {
                $amount *= 1000000;
            } elseif ($unit === 'k') {
                $amount *= 1000;
            } elseif ($unit === 'usd') {
                $result['currency'] = 'USD';
            }
            
            $result['amount'] = $amount;
        }

        // Simple category extraction (could be improved)
        if (str_contains($text, 'lunch') || str_contains($text, 'food')) {
            $result['category'] = 'Food';
        } elseif (str_contains($text, 'salary')) {
            $result['category'] = 'Work';
        } elseif (str_contains($text, 'from')) {
            $result['category'] = 'Personal';
        }

        return $result;
    }
}
