<?php

namespace App\Services\AI;

use App\Models\AIUsage;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PerformanceIntegrationService
{
    /**
     * Analyze AI-generated content performance.
     */
    public function analyzeAIContentPerformance(int $userId, int $days = 30): array
    {
        $startDate = now()->subDays($days);

        // Get AI-generated content with performance metrics
        $aiContent = AIUsage::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('content_id')
            ->where('suggestion_accepted', true)
            ->with('content')
            ->get();

        $analysis = [
            'title_performance' => $this->analyzeTitlePerformance($aiContent),
            'meta_description_performance' => $this->analyzeMetaDescriptionPerformance($aiContent),
            'tag_performance' => $this->analyzeTagPerformance($aiContent),
            'content_performance' => $this->analyzeContentPerformance($aiContent),
            'recommendations' => [],
        ];

        $analysis['recommendations'] = $this->generatePerformanceRecommendations($analysis);

        return $analysis;
    }

    /**
     * Analyze title performance patterns.
     */
    private function analyzeTitlePerformance($aiContent): array
    {
        $titleData = $aiContent->where('operation_type', 'title_generation')
            ->filter(function ($usage) {
                return $usage->content && $usage->content->view_count;
            });

        if ($titleData->isEmpty()) {
            return ['pattern' => 'insufficient_data', 'insights' => []];
        }

        $insights = [];
        $titles = $titleData->map(function ($usage) {
            return [
                'title' => $usage->content->title ?? '',
                'views' => $usage->content->view_count ?? 0,
                'length' => strlen($usage->content->title ?? ''),
            ];
        });

        // Analyze title length vs performance
        $shortTitles = $titles->filter(fn($t) => $t['length'] < 50);
        $longTitles = $titles->filter(fn($t) => $t['length'] >= 50);

        if ($shortTitles->isNotEmpty() && $longTitles->isNotEmpty()) {
            $shortAvgViews = $shortTitles->avg('views');
            $longAvgViews = $longTitles->avg('views');

            if ($shortAvgViews > $longAvgViews * 1.2) {
                $insights[] = [
                    'type' => 'title_length',
                    'message' => 'Shorter titles (< 50 chars) get ' . round(($shortAvgViews / $longAvgViews - 1) * 100) . '% more views',
                    'recommendation' => 'Keep AI-generated titles concise and under 50 characters',
                    'confidence' => 'high'
                ];
            }
        }

        // Analyze numbers in titles
        $titlesWithNumbers = $titles->filter(fn($t) => preg_match('/\d+/', $t['title']));
        $titlesWithoutNumbers = $titles->filter(fn($t) => !preg_match('/\d+/', $t['title']));

        if ($titlesWithNumbers->isNotEmpty() && $titlesWithoutNumbers->isNotEmpty()) {
            $withNumbersAvg = $titlesWithNumbers->avg('views');
            $withoutNumbersAvg = $titlesWithoutNumbers->avg('views');

            if ($withNumbersAvg > $withoutNumbersAvg * 1.15) {
                $insights[] = [
                    'type' => 'numbers_in_title',
                    'message' => 'Titles with numbers get ' . round(($withNumbersAvg / $withoutNumbersAvg - 1) * 100) . '% more views',
                    'recommendation' => 'Include numbers in AI-generated titles when appropriate',
                    'confidence' => 'medium'
                ];
            }
        }

        return [
            'pattern' => 'analyzed',
            'insights' => $insights,
            'sample_size' => $titles->count(),
            'avg_views' => $titles->avg('views'),
        ];
    }

    /**
     * Analyze meta description performance.
     */
    private function analyzeMetaDescriptionPerformance($aiContent): array
    {
        $metaData = $aiContent->where('operation_type', 'meta_description')
            ->filter(function ($usage) {
                return $usage->content && $usage->content->meta_description;
            });

        if ($metaData->isEmpty()) {
            return ['pattern' => 'insufficient_data', 'insights' => []];
        }

        $insights = [];
        $descriptions = $metaData->map(function ($usage) {
            return [
                'description' => $usage->content->meta_description ?? '',
                'views' => $usage->content->view_count ?? 0,
                'length' => strlen($usage->content->meta_description ?? ''),
            ];
        });

        // Analyze description length vs performance
        $shortDesc = $descriptions->filter(fn($d) => $d['length'] < 120);
        $longDesc = $descriptions->filter(fn($d) => $d['length'] >= 120);

        if ($shortDesc->isNotEmpty() && $longDesc->isNotEmpty()) {
            $shortAvgViews = $shortDesc->avg('views');
            $longAvgViews = $longDesc->avg('views');

            if ($shortAvgViews > $longAvgViews * 1.1) {
                $insights[] = [
                    'type' => 'description_length',
                    'message' => 'Shorter meta descriptions (< 120 chars) get ' . round(($shortAvgViews / $longAvgViews - 1) * 100) . '% more views',
                    'recommendation' => 'Keep AI-generated meta descriptions under 120 characters',
                    'confidence' => 'medium'
                ];
            }
        }

        return [
            'pattern' => 'analyzed',
            'insights' => $insights,
            'sample_size' => $descriptions->count(),
            'avg_views' => $descriptions->avg('views'),
        ];
    }

    /**
     * Analyze tag performance.
     */
    private function analyzeTagPerformance($aiContent): array
    {
        $tagData = $aiContent->where('operation_type', 'tag_generation')
            ->filter(function ($usage) {
                return $usage->content && $usage->content->tags()->exists();
            });

        if ($tagData->isEmpty()) {
            return ['pattern' => 'insufficient_data', 'insights' => []];
        }

        $insights = [];
        $taggedPosts = $tagData->map(function ($usage) {
            return [
                'tags' => $usage->content->tags->pluck('name')->toArray(),
                'views' => $usage->content->view_count ?? 0,
                'tag_count' => $usage->content->tags->count(),
            ];
        });

        // Analyze number of tags vs performance
        $fewTags = $taggedPosts->filter(fn($p) => $p['tag_count'] <= 3);
        $manyTags = $taggedPosts->filter(fn($p) => $p['tag_count'] > 3);

        if ($fewTags->isNotEmpty() && $manyTags->isNotEmpty()) {
            $fewAvgViews = $fewTags->avg('views');
            $manyAvgViews = $manyTags->avg('views');

            if ($fewAvgViews > $manyAvgViews * 1.1) {
                $insights[] = [
                    'type' => 'tag_count',
                    'message' => 'Posts with fewer tags (≤3) get ' . round(($fewAvgViews / $manyAvgViews - 1) * 100) . '% more views',
                    'recommendation' => 'Use AI to generate 3 or fewer focused tags',
                    'confidence' => 'medium'
                ];
            }
        }

        return [
            'pattern' => 'analyzed',
            'insights' => $insights,
            'sample_size' => $taggedPosts->count(),
            'avg_views' => $taggedPosts->avg('views'),
        ];
    }

    /**
     * Analyze overall content performance.
     */
    private function analyzeContentPerformance($aiContent): array
    {
        $contentData = $aiContent->filter(function ($usage) {
            return $usage->content && $usage->content->view_count;
        });

        if ($contentData->isEmpty()) {
            return ['pattern' => 'insufficient_data', 'insights' => []];
        }

        $aiGeneratedPosts = $contentData->map(function ($usage) {
            return [
                'views' => $usage->content->view_count ?? 0,
                'created_at' => $usage->content->created_at,
            ];
        });

        // Compare with non-AI posts
        $nonAIPosts = Post::where('user_id', $aiContent->first()->user_id)
            ->where('created_at', '>=', now()->subDays(30))
            ->where('view_count', '>', 0)
            ->whereNotIn('id', $aiContent->pluck('content_id'))
            ->get()
            ->map(function ($post) {
                return [
                    'views' => $post->view_count,
                    'created_at' => $post->created_at,
                ];
            });

        $insights = [];

        if ($nonAIPosts->isNotEmpty()) {
            $aiAvgViews = $aiGeneratedPosts->avg('views');
            $nonAiAvgViews = $nonAIPosts->avg('views');

            if ($aiAvgViews > $nonAiAvgViews * 1.1) {
                $insights[] = [
                    'type' => 'ai_vs_manual',
                    'message' => 'AI-assisted content gets ' . round(($aiAvgViews / $nonAiAvgViews - 1) * 100) . '% more views on average',
                    'recommendation' => 'Continue using AI assistance for content creation',
                    'confidence' => 'high'
                ];
            } elseif ($nonAiAvgViews > $aiAvgViews * 1.1) {
                $insights[] = [
                    'type' => 'ai_vs_manual',
                    'message' => 'Manual content performs ' . round(($nonAiAvgViews / $aiAvgViews - 1) * 100) . '% better than AI-assisted content',
                    'recommendation' => 'Review AI suggestions more carefully before accepting them',
                    'confidence' => 'medium'
                ];
            }
        }

        return [
            'pattern' => 'analyzed',
            'insights' => $insights,
            'sample_size' => $aiGeneratedPosts->count(),
            'avg_views' => $aiGeneratedPosts->avg('views'),
        ];
    }

    /**
     * Generate performance-based recommendations.
     */
    private function generatePerformanceRecommendations(array $analysis): array
    {
        $recommendations = [];

        // Collect all insights
        $allInsights = collect([
            $analysis['title_performance']['insights'] ?? [],
            $analysis['meta_description_performance']['insights'] ?? [],
            $analysis['tag_performance']['insights'] ?? [],
            $analysis['content_performance']['insights'] ?? [],
        ])->flatten(1);

        // Group by confidence and type
        $highConfidence = $allInsights->where('confidence', 'high');
        $mediumConfidence = $allInsights->where('confidence', 'medium');

        // Add high-confidence recommendations
        foreach ($highConfidence as $insight) {
            $recommendations[] = [
                'type' => 'performance_optimization',
                'title' => 'High Impact Optimization',
                'message' => $insight['message'],
                'action' => $insight['recommendation'],
                'priority' => 'high',
                'category' => $insight['type']
            ];
        }

        // Add medium-confidence recommendations
        foreach ($mediumConfidence->take(3) as $insight) {
            $recommendations[] = [
                'type' => 'performance_optimization',
                'title' => 'Performance Insight',
                'message' => $insight['message'],
                'action' => $insight['recommendation'],
                'priority' => 'medium',
                'category' => $insight['type']
            ];
        }

        // Add general recommendations if no specific insights
        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'general',
                'title' => 'Keep Using AI',
                'message' => 'Your AI-assisted content is performing well. Continue using AI suggestions to maintain quality.',
                'action' => 'Keep using AI features as you have been',
                'priority' => 'low',
                'category' => 'general'
            ];
        }

        return $recommendations;
    }

    /**
     * Get smart AI suggestions based on performance data.
     */
    public function getSmartAISuggestions(int $userId, string $operationType): array
    {
        $performanceData = $this->analyzeAIContentPerformance($userId);

        $suggestions = [
            'operation_type' => $operationType,
            'performance_insights' => [],
            'recommended_settings' => [],
        ];

        switch ($operationType) {
            case 'title_generation':
                $titleInsights = $performanceData['title_performance']['insights'] ?? [];
                foreach ($titleInsights as $insight) {
                    if ($insight['type'] === 'title_length' && $insight['confidence'] === 'high') {
                        $suggestions['recommended_settings'][] = 'Keep titles under 50 characters for better performance';
                    }
                    if ($insight['type'] === 'numbers_in_title' && $insight['confidence'] === 'medium') {
                        $suggestions['recommended_settings'][] = 'Include numbers in titles when appropriate';
                    }
                }
                break;

            case 'meta_description':
                $metaInsights = $performanceData['meta_description_performance']['insights'] ?? [];
                foreach ($metaInsights as $insight) {
                    if ($insight['type'] === 'description_length') {
                        $suggestions['recommended_settings'][] = 'Keep meta descriptions under 120 characters';
                    }
                }
                break;

            case 'tag_generation':
                $tagInsights = $performanceData['tag_performance']['insights'] ?? [];
                foreach ($tagInsights as $insight) {
                    if ($insight['type'] === 'tag_count') {
                        $suggestions['recommended_settings'][] = 'Generate 3 or fewer focused tags';
                    }
                }
                break;
        }

        return $suggestions;
    }
}
