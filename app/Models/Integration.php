<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'config',
        'is_connected',
        'status',
        'last_sync',
        'error_count',
        'success_rate',
        'response_time'
    ];

    protected $casts = [
        'config' => 'array',
        'is_connected' => 'boolean',
        'last_sync' => 'datetime',
        'error_count' => 'integer',
        'success_rate' => 'float',
        'response_time' => 'float'
    ];

    /**
     * Get integrations by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get connected integrations
     */
    public function scopeConnected($query)
    {
        return $query->where('is_connected', true);
    }

    /**
     * Get integration health status
     */
    public function getHealthStatusAttribute(): string
    {
        if (!$this->is_connected) {
            return 'disconnected';
        }

        if ($this->success_rate < 80) {
            return 'warning';
        }

        if ($this->success_rate < 50) {
            return 'error';
        }

        return 'healthy';
    }

    /**
     * Get integration icon
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'ecommerce' => '🛒',
            'marketing' => '📧',
            'analytics' => '📊',
            'social' => '📱',
            'crm' => '👥',
            'payment' => '💳',
            'communication' => '💬'
        ];

        return $icons[$this->category] ?? '🔗';
    }

    /**
     * Get integration display name
     */
    public function getDisplayNameAttribute(): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $this->name));
    }
}
