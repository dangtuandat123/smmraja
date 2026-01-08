<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'api_order_id',
        'link',
        'quantity',
        'price_per_unit',
        'total_price',
        'api_charge',
        'status',
        'start_count',
        'remains',
        'extra_data',
        'error_message',
        'refill_requested_at',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:5',
        'total_price' => 'decimal:2',
        'api_charge' => 'decimal:5',
        'quantity' => 'integer',
        'start_count' => 'integer',
        'remains' => 'integer',
        'extra_data' => 'array',
        'refill_requested_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PARTIAL = 'partial';
    const STATUS_CANCEL_PENDING = 'cancel_pending';
    const STATUS_REFILL_PENDING = 'refill_pending';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_ERROR = 'error';

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'in_progress' => 'Đang chạy',
            'completed' => 'Hoàn thành',
            'partial' => 'Hoàn thành một phần',
            'cancel_pending' => 'Chờ hoàn tiền',
            'refill_pending' => 'Đang bảo hành',
            'canceled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            'error' => 'Lỗi',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'partial' => 'warning',
            'cancel_pending' => 'warning',
            'refill_pending' => 'link',
            'canceled' => 'danger',
            'refunded' => 'info',
            'error' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_price, 0, ',', '.') . ' VND';
    }

    /**
     * Check if order can be canceled
     */
    public function canCancel(): bool
    {
        return in_array($this->status, ['pending', 'processing']) && $this->service->cancel;
    }

    /**
     * Check if order can be refilled
     */
    public function canRefill(): bool
    {
        return in_array($this->status, ['completed', 'partial']) && $this->service->refill;
    }

    /**
     * Scope for user's orders
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for orders that need status check from API
     */
    public function scopeNeedsStatusCheck($query)
    {
        return $query->whereNotNull('api_order_id')
            ->whereIn('status', [
                'pending', 
                'processing', 
                'in_progress',
                'refill_pending',   // Đang chờ bảo hành
                'cancel_pending',   // Đang chờ hủy
            ]);
    }
}
