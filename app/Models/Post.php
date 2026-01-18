<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'views',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->meta_title)) {
                $post->meta_title = $post->title;
            }
        });
    }

    /**
     * Relationship: Author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope: Published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Get excerpt or generate from content
     */
    public function getExcerptTextAttribute()
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        return Str::limit(strip_tags($this->content), 200);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('og-image.png');
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
