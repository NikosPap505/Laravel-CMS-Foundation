<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'ip_address',
        'user_agent',
        'is_guest',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
    ];

    // Relationships
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->approved();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // Accessors
    public function getAuthorNameAttribute($value)
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        }
        return $value;
    }

    public function getAuthorEmailAttribute($value)
    {
        if ($this->user_id && $this->user) {
            return $this->user->email;
        }
        return $value;
    }

    public function getAuthorAvatarAttribute()
    {
        if ($this->user_id && $this->user) {
            // You can implement Gravatar or custom avatar logic here
            return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->user->email)) . '?d=identicon&s=40';
        }
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->author_email)) . '?d=identicon&s=40';
    }

    // Methods
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function markAsSpam()
    {
        $this->update(['status' => 'spam']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isSpam(): bool
    {
        return $this->status === 'spam';
    }

    public function hasReplies(): bool
    {
        return $this->replies()->count() > 0;
    }
}