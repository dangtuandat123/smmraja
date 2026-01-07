<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'role',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get user's transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Add balance to user
     */
    public function addBalance(float $amount, string $type, string $description, ?int $orderId = null, ?string $adminNote = null, ?int $adminId = null): Transaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'order_id' => $orderId,
            'admin_note' => $adminNote,
            'admin_id' => $adminId,
        ]);
    }

    /**
     * Deduct balance from user
     */
    public function deductBalance(float $amount, string $type, string $description, ?int $orderId = null): Transaction
    {
        return $this->addBalance(-abs($amount), $type, $description, $orderId);
    }

    /**
     * Check if user has enough balance (uses floor for integer comparison)
     */
    public function hasBalance(int $amount): bool
    {
        return floor($this->balance) >= $amount;
    }

    /**
     * Format balance with currency
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 0, ',', '.') . ' VND';
    }
}
