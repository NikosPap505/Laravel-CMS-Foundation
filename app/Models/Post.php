<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Post extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')
              ->where('published_at', '<=', now());
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function featuredImage()
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    /**
     * Get related posts (same category, excluding current post).
     */
    public function related($limit = 3)
    {
        return self::published()
            ->where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function scopePopular($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
                    ->orderBy('view_count', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->approved()->topLevel();
    }

    public function pendingComments()
    {
        return $this->hasMany(Comment::class)->pending();
    }

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'published_at', 'category_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
