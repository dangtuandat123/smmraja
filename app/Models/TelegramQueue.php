<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\TelegramService;

class TelegramQueue extends Model
{
    protected $table = 'telegram_queue';
    
    protected $fillable = [
        'type',
        'message',
        'data',
        'sent',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Queue a new order notification
     */
    public static function queueOrder(int $orderId, string $userName, string $serviceName, int $quantity, int $totalPrice, string $link): self
    {
        $data = [
            'order_id' => $orderId,
            'user_name' => $userName,
            'service_name' => $serviceName,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'link' => $link,
        ];

        return self::create([
            'type' => 'order',
            'message' => TelegramService::formatOrderMessage($data),
            'data' => $data,
        ]);
    }

    /**
     * Queue a new user notification
     */
    public static function queueNewUser(string $email, string $name): self
    {
        $data = [
            'email' => $email,
            'name' => $name,
        ];

        return self::create([
            'type' => 'user',
            'message' => TelegramService::formatNewUserMessage($data),
            'data' => $data,
        ]);
    }

    /**
     * Queue an error notification
     */
    public static function queueError(string $errorMessage, ?string $url = null, ?string $user = null): self
    {
        $data = [
            'message' => $errorMessage,
            'url' => $url,
            'user' => $user,
        ];

        return self::create([
            'type' => 'error',
            'message' => TelegramService::formatErrorMessage($data),
            'data' => $data,
        ]);
    }

    /**
     * Queue balance warning
     */
    public static function queueBalanceWarning(float $balance, string $currency): self
    {
        return self::create([
            'type' => 'balance',
            'message' => TelegramService::formatBalanceWarning($balance, $currency),
            'data' => ['balance' => $balance, 'currency' => $currency],
        ]);
    }

    /**
     * Scope for unsent messages
     */
    public function scopeUnsent($query)
    {
        return $query->where('sent', false);
    }

    /**
     * Mark as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'sent' => true,
            'sent_at' => now(),
        ]);
    }
}
