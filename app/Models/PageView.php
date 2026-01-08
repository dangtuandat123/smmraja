<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'ip_address',
        'user_id',
        'user_agent',
        'viewed_date',
    ];

    protected $casts = [
        'viewed_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Track a page view
     */
    public static function track(string $path, ?int $userId = null): self
    {
        return self::create([
            'path' => $path,
            'ip_address' => request()->ip(),
            'user_id' => $userId,
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            'viewed_date' => now()->toDateString(),
        ]);
    }
}
