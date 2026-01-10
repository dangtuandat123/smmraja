<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class TelegramService
{
    private ?string $botToken;
    private ?string $chatId;
    private bool $enabled;

    public function __construct()
    {
        $this->botToken = Setting::get('telegram_bot_token');
        $this->chatId = Setting::get('telegram_chat_id');
        $this->enabled = (bool) Setting::get('telegram_enabled', false);
    }

    /**
     * Check if Telegram is configured
     */
    public function isConfigured(): bool
    {
        return $this->enabled && !empty($this->botToken) && !empty($this->chatId);
    }

    /**
     * Send message to Telegram
     */
    public function sendMessage(string $message, ?string $parseMode = 'HTML'): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Telegram not configured, skipping message');
            return false;
        }

        try {
            $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
            
            $response = Http::timeout(10)->post($url, [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful() && $response->json('ok')) {
                return true;
            }

            Log::error('Telegram API error', [
                'response' => $response->json(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Telegram send failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Format order notification
     */
    public static function formatOrderMessage(array $orderData): string
    {
        return "🛒 <b>Đơn hàng mới #{$orderData['order_id']}</b>\n\n"
            . "👤 Khách: {$orderData['user_name']}\n"
            . "📦 Dịch vụ: {$orderData['service_name']}\n"
            . "🔢 Số lượng: " . number_format($orderData['quantity']) . "\n"
            . "💰 Giá: " . number_format($orderData['total_price']) . " VND\n"
            . "🔗 Link: {$orderData['link']}\n"
            . "⏰ " . now()->format('H:i d/m/Y');
    }

    /**
     * Format new user notification
     */
    public static function formatNewUserMessage(array $userData): string
    {
        return "👋 <b>Người dùng mới đăng ký</b>\n\n"
            . "📧 Email: {$userData['email']}\n"
            . "👤 Tên: {$userData['name']}\n"
            . "⏰ " . now()->format('H:i d/m/Y');
    }

    /**
     * Format error notification
     */
    public static function formatErrorMessage(array $errorData): string
    {
        $message = "🚨 <b>Lỗi hệ thống</b>\n\n"
            . "❌ {$errorData['message']}\n";
        
        if (!empty($errorData['url'])) {
            $message .= "🔗 URL: {$errorData['url']}\n";
        }
        if (!empty($errorData['user'])) {
            $message .= "👤 User: {$errorData['user']}\n";
        }
        
        $message .= "⏰ " . now()->format('H:i d/m/Y');
        
        return $message;
    }

    /**
     * Format balance warning
     */
    public static function formatBalanceWarning(float $balance, string $currency): string
    {
        return "⚠️ <b>Cảnh báo số dư SMM Raja thấp!</b>\n\n"
            . "💰 Số dư hiện tại: \${$balance} {$currency}\n"
            . "⏰ " . now()->format('H:i d/m/Y');
    }
}
