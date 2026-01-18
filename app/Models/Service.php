<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\ExchangeRateService;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'api_service_id',
        'name',
        'api_name',
        'type',
        'description',
        'icon',
        'icon_color',
        'api_rate',
        'markup_percent',
        'price_vnd',
        'min',
        'max',
        'refill',
        'cancel',
        'is_active',
        'sort_order',
        'extra_parameters',
    ];

    protected $casts = [
        'api_rate' => 'decimal:5',
        'markup_percent' => 'decimal:2',
        'price_vnd' => 'decimal:2',
        'min' => 'integer',
        'max' => 'integer',
        'refill' => 'boolean',
        'cancel' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'extra_parameters' => 'array',
    ];

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get orders for this service
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Calculate price in VND based on api_rate and markup
     * Uses realtime exchange rate
     * 
     * @param float|null $exchangeRate VND per USD (if null, fetches realtime)
     * @return int Price per 1000 in VND (integer)
     */
    public function calculatePriceVnd(?float $exchangeRate = null): int
    {
        $exchangeRate = $exchangeRate ?? ExchangeRateService::getRate();
        
        // api_rate is price per 1000 in USD
        // Apply markup percentage
        $priceUsd = $this->api_rate * (1 + $this->markup_percent / 100);
        
        return (int) round($priceUsd * $exchangeRate);
    }

    /**
     * Update price_vnd based on current settings
     */
    public function updatePriceVnd(?float $exchangeRate = null): void
    {
        $this->price_vnd = $this->calculatePriceVnd($exchangeRate);
        $this->save();
    }

    /**
     * Calculate order total for given quantity
     * 
     * @param int $quantity
     * @return int Total in VND (integer)
     */
    public function calculateOrderTotal(int $quantity): int
    {
        // price_vnd is per 1000, round to integer
        return (int) round(($this->price_vnd / 1000) * $quantity);
    }

    /**
     * Get price per unit (per 1) in VND
     */
    public function getPricePerUnitAttribute(): float
    {
        return $this->price_vnd / 1000;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_vnd, 0, ',', '.') . ' VND/1000';
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('services.is_active', true);
    }

    /**
     * Scope for ordered services
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get service type display name
     */
    public function getTypeDisplayAttribute(): string
    {
        $types = [
            'Default' => 'Mặc định',
            'Custom Comments' => 'Bình luận tùy chỉnh',
            'Custom Comments Package' => 'Gói bình luận tùy chỉnh',
            'Mentions' => 'Đề cập',
            'Mentions with Hashtags' => 'Đề cập với Hashtags',
            'Mentions Custom List' => 'Đề cập danh sách tùy chỉnh',
            'Mentions Hashtag' => 'Đề cập Hashtag',
            'Mentions User Followers' => 'Đề cập Follower người dùng',
            'Mentions Media Likers' => 'Đề cập người Like',
            'Comment Likes' => 'Like bình luận',
            'Comment Replies' => 'Trả lời bình luận',
            'Poll' => 'Bình chọn',
            'Invites from Groups' => 'Mời từ nhóm',
            'Package' => 'Gói dịch vụ',
            'Subscriptions' => 'Đăng ký theo dõi',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
