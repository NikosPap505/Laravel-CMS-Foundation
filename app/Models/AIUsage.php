<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIUsage extends Model
{
    use HasFactory;

    protected $table = 'ai_usage';

    protected $fillable = [
        'user_id',
        'operation_type',
        'provider',
        'tokens_used',
        'cost',
        'status',
        'metadata',
        'success_rate',
        'content_id',
        'content_type',
        'suggestion_accepted',
    ];

    protected $casts = [
        'metadata' => 'array',
        'cost' => 'decimal:4',
        'success_rate' => 'decimal:2',
        'suggestion_accepted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the AI usage record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get usage statistics for a user.
     */
    public static function getUserStats(int $userId): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $totalUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->sum('cost');

        $todayUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $today)
            ->sum('cost');

        $monthUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('cost');

        $todayRequests = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $today)
            ->count();

        $monthRequests = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Get usage breakdown by operation type
        $usageBreakdown = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->selectRaw('operation_type, COUNT(*) as count, SUM(cost) as total_cost')
            ->groupBy('operation_type')
            ->get()
            ->keyBy('operation_type')
            ->map(function ($item) {
                return [
                    'count' => $item->count,
                    'total_cost' => $item->total_cost
                ];
            });

        return [
            'total_cost' => $totalUsage,
            'today_cost' => $todayUsage,
            'month_cost' => $monthUsage,
            'today_requests' => $todayRequests,
            'month_requests' => $monthRequests,
            'usage_breakdown' => $usageBreakdown,
            'last_used' => static::where('user_id', $userId)
                ->where('status', 'completed')
                ->latest()
                ->first()?->created_at?->toISOString(),
        ];
    }

    /**
     * Get remaining credits for a user (assuming $100 starting balance).
     */
    public static function getRemainingCredits(int $userId): float
    {
        $totalUsed = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->sum('cost');

        return max(0, 100.00 - $totalUsed);
    }

    /**
     * Get usage status based on remaining credits.
     */
    public static function getUsageStatus(int $userId): string
    {
        $remaining = static::getRemainingCredits($userId);

        if ($remaining <= 0) {
            return 'exhausted';
        } elseif ($remaining <= 10) {
            return 'low';
        } elseif ($remaining <= 25) {
            return 'moderate';
        } else {
            return 'healthy';
        }
    }

    /**
     * Calculate usage percentage.
     */
    public static function getUsagePercentage(int $userId): float
    {
        $totalUsed = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->sum('cost');

        return min(100, ($totalUsed / 100.00) * 100);
    }

    /**
     * Get advanced analytics for AI usage.
     */
    public static function getAdvancedAnalytics(int $userId): array
    {
        $thirtyDaysAgo = now()->subDays(30);

        // Feature usage statistics
        $featureUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('operation_type, COUNT(*) as total_requests, AVG(cost) as avg_cost, SUM(CASE WHEN suggestion_accepted = 1 THEN 1 ELSE 0 END) as accepted_suggestions')
            ->groupBy('operation_type')
            ->get()
            ->map(function ($item) {
                return [
                    'operation_type' => $item->operation_type,
                    'total_requests' => $item->total_requests,
                    'avg_cost' => round($item->avg_cost, 4),
                    'accepted_suggestions' => $item->accepted_suggestions,
                    'success_rate' => $item->total_requests > 0 ? round(($item->accepted_suggestions / $item->total_requests) * 100, 1) : 0,
                ];
            });

        // Daily usage trends
        $dailyUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as requests, SUM(cost) as daily_cost')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Peak usage hours
        $hourlyUsage = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as requests')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Cost efficiency analysis
        $costEfficiency = static::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('operation_type, SUM(cost) as total_cost, SUM(CASE WHEN suggestion_accepted = 1 THEN 1 ELSE 0 END) as successful_requests')
            ->groupBy('operation_type')
            ->get()
            ->map(function ($item) {
                return [
                    'operation_type' => $item->operation_type,
                    'cost_per_success' => $item->successful_requests > 0 ? round($item->total_cost / $item->successful_requests, 4) : 0,
                    'total_cost' => $item->total_cost,
                    'successful_requests' => $item->successful_requests,
                ];
            });

        return [
            'feature_usage' => $featureUsage,
            'daily_usage' => $dailyUsage,
            'hourly_usage' => $hourlyUsage,
            'cost_efficiency' => $costEfficiency,
            'optimization_tips' => static::generateOptimizationTips($featureUsage, $costEfficiency),
        ];
    }

    /**
     * Generate optimization tips based on usage patterns.
     */
    public static function generateOptimizationTips($featureUsage, $costEfficiency): array
    {
        $tips = [];

        // Ensure we have valid collections
        if (!$featureUsage || $featureUsage->isEmpty()) {
            return [
                [
                    'type' => 'getting_started',
                    'message' => "Start using AI features to see personalized optimization tips and insights.",
                    'priority' => 'low'
                ]
            ];
        }

        // Find most used feature
        $mostUsedFeature = $featureUsage->sortByDesc('total_requests')->first();
        if ($mostUsedFeature && isset($mostUsedFeature['operation_type']) && isset($mostUsedFeature['total_requests'])) {
            $tips[] = [
                'type' => 'usage_pattern',
                'message' => "You use {$mostUsedFeature['operation_type']} most frequently ({$mostUsedFeature['total_requests']} times). Consider using our bulk generation features to save time.",
                'priority' => 'medium'
            ];
        }

        // Check success rates
        $lowSuccessFeatures = $featureUsage->where('success_rate', '<', 50);
        if ($lowSuccessFeatures->count() > 0) {
            $tips[] = [
                'type' => 'success_rate',
                'message' => "Some AI features have low success rates. Try adjusting your prompts or using different tones for better results.",
                'priority' => 'high'
            ];
        }

        // Cost efficiency tips
        if ($costEfficiency && $costEfficiency->isNotEmpty()) {
            $expensiveFeatures = $costEfficiency->where('cost_per_success', '>', 0.05);
            if ($expensiveFeatures->count() > 0) {
                $tips[] = [
                    'type' => 'cost_efficiency',
                    'message' => "Consider using more specific prompts to reduce costs on expensive operations.",
                    'priority' => 'medium'
                ];
            }
        }

        // Peak usage tips
        $totalRequests = $featureUsage->sum('total_requests');
        if ($totalRequests > 50) {
            $tips[] = [
                'type' => 'volume',
                'message' => "You're a power user! Consider upgrading to a higher credit limit for better productivity.",
                'priority' => 'low'
            ];
        }

        // If no specific tips, add a general encouragement
        if (empty($tips)) {
            $tips[] = [
                'type' => 'general',
                'message' => "Great job using AI features! Your usage patterns look healthy.",
                'priority' => 'low'
            ];
        }

        return $tips;
    }

    /**
     * Track AI suggestion acceptance.
     */
    public static function trackSuggestionAcceptance(int $userId, string $operationType, bool $accepted, ?int $contentId = null, ?string $contentType = null): void
    {
        static::create([
            'user_id' => $userId,
            'operation_type' => $operationType,
            'provider' => config('ai.default', 'gemini'),
            'tokens_used' => 0, // We'll calculate this based on operation type
            'cost' => static::calculateOperationCost($operationType),
            'status' => 'completed',
            'suggestion_accepted' => $accepted,
            'content_id' => $contentId,
            'content_type' => $contentType,
            'metadata' => [
                'timestamp' => now()->toISOString(),
                'accepted' => $accepted,
            ],
        ]);
    }

    /**
     * Calculate estimated cost for different operation types.
     */
    public static function calculateOperationCost(string $operationType): float
    {
        $costs = [
            'title_generation' => 0.001,
            'meta_description' => 0.002,
            'excerpt_generation' => 0.003,
            'tag_generation' => 0.002,
            'content_improvement' => 0.005,
            'blog_post_generation' => 0.02,
            'social_media_post' => 0.003,
            'seo_optimization' => 0.004,
        ];

        return $costs[$operationType] ?? 0.001;
    }
}
