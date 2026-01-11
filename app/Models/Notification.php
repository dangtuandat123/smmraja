<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'color',
        'link',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Notification types
     */
    const TYPE_ORDER = 'order';
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_SYSTEM = 'system';
    const TYPE_PROMO = 'promo';

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for user's notifications
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Create order notification
     */
    public static function orderCreated(int $userId, int $orderId, string $serviceName): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_ORDER,
            'title' => 'Đơn hàng mới',
            'message' => "Đơn hàng #{$orderId} ({$serviceName}) đã được tạo thành công.",
            'icon' => 'fa-shopping-bag',
            'color' => 'success',
            'link' => url('/orders/' . $orderId),
            'data' => ['order_id' => $orderId],
        ]);
    }

    /**
     * Create order status notification
     */
    public static function orderStatusChanged(int $userId, int $orderId, string $status): self
    {
        $statusMessages = [
            'processing' => 'đang được xử lý',
            'in_progress' => 'đang chạy',
            'completed' => 'đã hoàn thành',
            'partial' => 'đã hoàn thành một phần',
            'canceled' => 'đã bị hủy',
            'refunded' => 'đã được hoàn tiền',
            'error' => 'gặp lỗi',
        ];

        $message = $statusMessages[$status] ?? $status;
        $color = match($status) {
            'completed' => 'success',
            'processing', 'in_progress' => 'info',
            'partial' => 'warning',
            'canceled', 'error' => 'danger',
            'refunded' => 'info',
            default => 'primary',
        };

        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_ORDER,
            'title' => 'Cập nhật đơn hàng',
            'message' => "Đơn hàng #{$orderId} {$message}.",
            'icon' => 'fa-shopping-bag',
            'color' => $color,
            'link' => url('/orders/' . $orderId),
            'data' => ['order_id' => $orderId, 'status' => $status],
        ]);
    }

    /**
     * Create deposit notification
     */
    public static function depositSuccess(int $userId, float $amount): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_DEPOSIT,
            'title' => 'Nạp tiền thành công',
            'message' => 'Số dư của bạn đã được cộng ' . number_format($amount, 0, ',', '.') . ' VND.',
            'icon' => 'fa-wallet',
            'color' => 'success',
            'link' => route('wallet.history'),
            'data' => ['amount' => $amount],
        ]);
    }

    /**
     * Create system notification
     */
    public static function system(int $userId, string $title, string $message, ?string $link = null): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_SYSTEM,
            'title' => $title,
            'message' => $message,
            'icon' => 'fa-bell',
            'color' => 'info',
            'link' => $link,
        ]);
    }
}
