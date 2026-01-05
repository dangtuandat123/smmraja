<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'order_id',
        'admin_note',
        'admin_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Type constants
     */
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_ORDER = 'order';
    const TYPE_REFUND = 'refund';
    const TYPE_ADMIN_ADJUST = 'admin_adjust';

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order (if applicable)
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the admin (if applicable)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayAttribute(): string
    {
        $types = [
            'deposit' => 'Nạp tiền',
            'withdraw' => 'Rút tiền',
            'order' => 'Đặt hàng',
            'refund' => 'Hoàn tiền',
            'admin_adjust' => 'Điều chỉnh',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get type color for UI
     */
    public function getTypeColorAttribute(): string
    {
        $colors = [
            'deposit' => 'success',
            'withdraw' => 'danger',
            'order' => 'primary',
            'refund' => 'info',
            'admin_adjust' => 'warning',
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->amount >= 0 ? '+' : '';
        return $sign . number_format($this->amount, 0, ',', '.') . ' VND';
    }

    /**
     * Scope for user's transactions
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
