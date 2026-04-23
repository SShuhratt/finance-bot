<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    private const CBU_URL = 'https://cbu.uz/uz/arkhiv-kursov-valyut/json/';

    public function getRate(string $code = 'USD'): float
    {
        return Cache::remember("currency_rate_$code", 3600, function () use ($code) {
            $response = Http::get(self::CBU_URL);
            if ($response->successful()) {
                $rates = $response->json();
                foreach ($rates as $rate) {
                    if ($rate['Ccy'] === $code) {
                        return (float)$rate['Rate'];
                    }
                }
            }
            return 12500.0; // Fallback rate
        });
    }

    public function convertToUzs(float $amount, string $currency): float
    {
        if (strtoupper($currency) === 'UZS') {
            return $amount;
        }

        $rate = $this->getRate(strtoupper($currency));
        return $amount * $rate;
    }
}
