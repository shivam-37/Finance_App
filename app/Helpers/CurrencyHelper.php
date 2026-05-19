<?php

namespace App\Helpers;

class CurrencyHelper
{
    // Static lookup rates relative to INR (base).
    protected static $rates = [
        'INR' => 1.0,
        'USD' => 83.5,
        'EUR' => 90.2,
        'GBP' => 105.8,
        'JPY' => 0.53,
        'AED' => 22.7,
    ];

    public static function convert(float $amount, string $from, string $to = 'INR'): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        $fromRate = self::$rates[$from] ?? 1.0;
        $toRate = self::$rates[$to] ?? 1.0;

        // Convert amount from source currency to base (INR), then to target currency
        $amountInBase = $amount * $fromRate;
        return round($amountInBase / $toRate, 2);
    }

    public static function getSymbols(): array
    {
        return [
            'INR' => '₹',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'AED' => 'د.إ',
        ];
    }

    public static function getSymbol(string $currency): string
    {
        return self::getSymbols()[strtoupper($currency)] ?? '₹';
    }
}
