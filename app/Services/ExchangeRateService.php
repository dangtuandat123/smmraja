<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Exception;

class ExchangeRateService
{
    /**
     * Cache key for exchange rate
     */
    const CACHE_KEY = 'usd_to_vnd_rate';
    
    /**
     * Cache duration in seconds (30 minutes)
     */
    const CACHE_DURATION = 1800;
    
    /**
     * Default rate if API fails
     */
    const DEFAULT_RATE = 27000;

    /**
     * Get default rate from database or constant
     */
    protected static function getDefaultRate(): float
    {
        return (float) (Setting::get('exchange_rate') ?: self::DEFAULT_RATE);
    }

    /**
     * Get current USD to VND exchange rate
     * 
     * @param bool $forceRefresh Force refresh from API
     * @return float
     */
    public static function getRate(bool $forceRefresh = false): float
    {
        if ($forceRefresh) {
            Cache::forget(self::CACHE_KEY);
        }

        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return self::fetchFromApi();
        });
    }

    /**
     * Fetch exchange rate from API
     * Uses exchangerate-api.com (free tier)
     */
    protected static function fetchFromApi(): float
    {
        // Try multiple sources for reliability
        $sources = [
            'exchangerate_api' => fn() => self::fetchFromExchangeRateApi(),
            'vietcombank' => fn() => self::fetchFromVietcombank(),
        ];

        foreach ($sources as $name => $fetcher) {
            try {
                $rate = $fetcher();
                if ($rate > 0) {
                    Log::info("Exchange rate fetched from {$name}", ['rate' => $rate]);
                    return $rate;
                }
            } catch (Exception $e) {
                Log::warning("Failed to fetch from {$name}", ['error' => $e->getMessage()]);
            }
        }

        // Fallback to database or default
        $fallbackRate = self::getDefaultRate();
        Log::warning('Using fallback exchange rate', ['rate' => $fallbackRate]);
        return $fallbackRate;
    }

    /**
     * Fetch from exchangerate-api.com (free, no key required)
     */
    protected static function fetchFromExchangeRateApi(): float
    {
        $response = Http::timeout(10)
            ->get('https://api.exchangerate-api.com/v4/latest/USD');

        if ($response->successful()) {
            $data = $response->json();
            return (float) ($data['rates']['VND'] ?? 0);
        }

        throw new Exception('ExchangeRate API request failed');
    }

    /**
     * Fetch from Vietcombank (backup)
     */
    protected static function fetchFromVietcombank(): float
    {
        $response = Http::timeout(10)
            ->get('https://www.vietcombank.com.vn/api/exchangerates?date=' . date('Y-m-d'));

        if ($response->successful()) {
            $data = $response->json();
            foreach ($data['data'] ?? [] as $currency) {
                if (($currency['currencyCode'] ?? '') === 'USD') {
                    // Use selling rate
                    return (float) str_replace(',', '', $currency['sell'] ?? 0);
                }
            }
        }

        throw new Exception('Vietcombank API request failed');
    }

    /**
     * Get rate info with metadata
     */
    public static function getRateInfo(): array
    {
        $cachedAt = Cache::get(self::CACHE_KEY . '_time');
        
        return [
            'rate' => self::getRate(),
            'cached_at' => $cachedAt,
            'expires_in' => Cache::has(self::CACHE_KEY) 
                ? self::CACHE_DURATION - (time() - ($cachedAt ?? time()))
                : 0,
            'source' => 'exchangerate-api.com',
        ];
    }

    /**
     * Force refresh and return new rate
     */
    public static function refresh(): float
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY . '_time');
        
        $rate = self::getRate();
        Cache::put(self::CACHE_KEY . '_time', time(), self::CACHE_DURATION);
        
        return $rate;
    }
}
