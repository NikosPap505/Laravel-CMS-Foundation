<?php

namespace App\Services\AI;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OpenAIProvider;
use App\Services\AI\Providers\GeminiProvider;
use App\Models\AIUsage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class AIService
{
    protected AIProviderInterface $provider;
    protected array $config;

    public function __construct()
    {
        $this->config = config('ai');
        $this->initializeProvider();
    }

    /**
     * Initialize the AI provider based on configuration.
     */
    protected function initializeProvider(): void
    {
        $providerName = $this->config['default'] ?? 'openai';

        try {
            switch ($providerName) {
                case 'openai':
                    $this->provider = new OpenAIProvider();
                    break;
                case 'gemini':
                    $this->provider = new GeminiProvider();
                    break;
                // Add more providers here as they are implemented
                default:
                    throw new Exception("Unsupported AI provider: {$providerName}");
            }

            if (!$this->provider->isAvailable()) {
                throw new Exception("AI provider '{$providerName}' is not properly configured");
            }
        } catch (Exception $e) {
            // Create a null provider for development when AI is not configured
            $this->provider = new class implements \App\Services\AI\Contracts\AIProviderInterface {
                public function generateContent(string $prompt, array $parameters = []): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateBlogPost(string $topic, array $options = []): array
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateMetaDescription(string $title, string $content = '', array $options = []): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateTitleSuggestions(string $topic, array $options = []): array
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function improveContent(string $content, array $options = []): array
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateTags(string $content, array $options = []): array
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateImageAltText(string $imageUrl, string $context = ''): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function translateContent(string $content, string $targetLanguage, array $options = []): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function analyzeSentiment(string $content): array
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateProductDescription(string $productName, array $features = [], array $options = []): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function generateSocialMediaPost(string $content, string $platform = 'twitter', array $options = []): string
                {
                    throw new Exception('AI service not configured. Please set your AI provider API key (OpenAI or Gemini).');
                }
                public function isAvailable(): bool
                {
                    return false;
                }
                public function getConfig(): array
                {
                    return [];
                }
                public function getUsageStats(): array
                {
                    return [];
                }
            };
        }
    }

    /**
     * Generate a complete blog post with all metadata.
     *
     * @param string $topic
     * @param array $options
     * @return array
     */
    public function generateBlogPost(string $topic, array $options = []): array
    {
        try {
            $result = $this->provider->generateBlogPost($topic, $options);

            // Log successful generation
            Log::info('AI Blog Post Generated', [
                'topic' => $topic,
                'options' => $options,
                'title' => $result['title'] ?? 'N/A'
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('AI Blog Post Generation Failed', [
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate SEO-optimized meta description.
     *
     * @param string $title
     * @param string $content
     * @param array $options
     * @return string
     */
    public function generateMetaDescription(string $title, string $content = '', array $options = []): string
    {
        $result = $this->provider->generateMetaDescription($title, $content, $options);
        $this->trackUsage('meta_description', 0.01);
        return $result;
    }

    /**
     * Generate title suggestions for a topic.
     *
     * @param string $topic
     * @param array $options
     * @return array
     */
    public function generateTitleSuggestions(string $topic, array $options = []): array
    {
        $result = $this->provider->generateTitleSuggestions($topic, $options);
        $this->trackUsage('title_generation', 0.02);
        return $result;
    }

    /**
     * Improve existing content.
     *
     * @param string $content
     * @param array $options
     * @return array
     */
    public function improveContent(string $content, array $options = []): array
    {
        $result = $this->provider->improveContent($content, $options);
        $this->trackUsage('content_improvement', 0.03);
        return $result;
    }

    /**
     * Generate tags for content.
     *
     * @param string $content
     * @param array $options
     * @return array
     */
    public function generateTags(string $content, array $options = []): array
    {
        $tags = $this->provider->generateTags($content, $options);
        $this->trackUsage('tag_generation', 0.01);

        // Filter and clean tags
        return array_filter(array_map(function ($tag) {
            return trim(strtolower($tag));
        }, $tags));
    }

    /**
     * Generate alt text for images.
     *
     * @param string $imageUrl
     * @param string $context
     * @return string
     */
    public function generateImageAltText(string $imageUrl, string $context = ''): string
    {
        return $this->provider->generateImageAltText($imageUrl, $context);
    }

    /**
     * Translate content to another language.
     *
     * @param string $content
     * @param string $targetLanguage
     * @param array $options
     * @return string
     */
    public function translateContent(string $content, string $targetLanguage, array $options = []): string
    {
        if (!$this->isTranslationEnabled()) {
            throw new Exception('Translation feature is not enabled');
        }

        return $this->provider->translateContent($content, $targetLanguage, $options);
    }

    /**
     * Analyze content sentiment.
     *
     * @param string $content
     * @return array
     */
    public function analyzeSentiment(string $content): array
    {
        if (!$this->isSentimentAnalysisEnabled()) {
            throw new Exception('Sentiment analysis feature is not enabled');
        }

        return $this->provider->analyzeSentiment($content);
    }

    /**
     * Generate product description.
     *
     * @param string $productName
     * @param array $features
     * @param array $options
     * @return string
     */
    public function generateProductDescription(string $productName, array $features = [], array $options = []): string
    {
        return $this->provider->generateProductDescription($productName, $features, $options);
    }

    /**
     * Generate social media post.
     *
     * @param string $content
     * @param string $platform
     * @param array $options
     * @return string
     */
    public function generateSocialMediaPost(string $content, string $platform = 'twitter', array $options = []): string
    {
        return $this->provider->generateSocialMediaPost($content, $platform, $options);
    }

    /**
     * Generate content with custom prompt.
     *
     * @param string $prompt
     * @param array $parameters
     * @return string
     */
    public function generateContent(string $prompt, array $parameters = []): string
    {
        return $this->provider->generateContent($prompt, $parameters);
    }

    /**
     * Auto-optimize a post for SEO.
     *
     * @param array $postData
     * @return array
     */
    public function optimizePostForSEO(array $postData): array
    {
        if (!$this->isSeoOptimizationEnabled()) {
            return $postData;
        }

        $optimized = $postData;

        try {
            // Generate meta description if missing
            if (empty($optimized['meta_description']) && !empty($optimized['title'])) {
                $optimized['meta_description'] = $this->generateMetaDescription(
                    $optimized['title'],
                    $optimized['body'] ?? '',
                    ['max_length' => 155]
                );
            }

            // Generate tags if missing
            if (empty($optimized['tags']) && !empty($optimized['body'])) {
                $generatedTags = $this->generateTags($optimized['body'], ['max_tags' => 8]);
                $optimized['suggested_tags'] = $generatedTags;
            }

            // Generate title suggestions if requested
            if (!empty($postData['generate_title_suggestions'])) {
                $optimized['title_suggestions'] = $this->generateTitleSuggestions(
                    $optimized['title'] ?? 'Blog Post',
                    ['count' => 5]
                );
            }

            return $optimized;
        } catch (Exception $e) {
            Log::error('SEO Optimization Failed', [
                'post_id' => $postData['id'] ?? 'new',
                'error' => $e->getMessage()
            ]);

            // Return original data if optimization fails
            return $postData;
        }
    }

    /**
     * Get comprehensive content analysis.
     *
     * @param string $content
     * @return array
     */
    public function analyzeContent(string $content): array
    {
        $analysis = [
            'word_count' => str_word_count($content),
            'character_count' => strlen($content),
            'paragraph_count' => count(array_filter(explode("\n\n", $content))),
            'reading_time' => $this->calculateReadingTime($content),
        ];

        try {
            // Add AI-powered analysis
            if ($this->isSentimentAnalysisEnabled()) {
                $analysis['sentiment'] = $this->analyzeSentiment($content);
                $this->trackUsage('content_analysis', 0.02);
            }

            // Add readability analysis through content improvement
            $improvements = $this->improveContent($content, ['analysis_only' => true]);
            if (isset($improvements['readability_score'])) {
                $analysis['readability'] = $improvements['readability_score'];
                $this->trackUsage('content_analysis', 0.02);
            }
        } catch (Exception $e) {
            Log::warning('Content Analysis Partial Failure', [
                'error' => $e->getMessage()
            ]);
        }

        return $analysis;
    }

    /**
     * Get AI usage statistics.
     *
     * @return array
     */
    public function getUsageStats(): array
    {
        $providerStats = $this->provider->getUsageStats();

        // Add local usage tracking
        $localStats = $this->getLocalUsageStats();

        return array_merge($providerStats, $localStats);
    }

    /**
     * Get local usage statistics from database.
     *
     * @return array
     */
    protected function getLocalUsageStats(): array
    {
        $userId = auth()->id();

        if (!$userId) {
            return [
                'total_requests' => 0,
                'requests_today' => 0,
                'requests_this_month' => 0,
                'estimated_cost' => 0.00,
                'credits_remaining' => 100.00,
                'last_used' => null,
                'usage_breakdown' => [],
                'usage_percentage' => 0,
                'status' => 'healthy'
            ];
        }

        $cacheKey = 'ai_usage_stats_' . $userId;

        return Cache::remember($cacheKey, 300, function () use ($userId) { // Cache for 5 minutes
            $stats = AIUsage::getUserStats($userId);

            return [
                'total_requests' => $stats['month_requests'],
                'requests_today' => $stats['today_requests'],
                'requests_this_month' => $stats['month_requests'],
                'estimated_cost' => $stats['total_cost'],
                'credits_remaining' => AIUsage::getRemainingCredits($userId),
                'last_used' => $stats['last_used'],
                'usage_breakdown' => $stats['usage_breakdown'],
                'usage_percentage' => AIUsage::getUsagePercentage($userId),
                'status' => AIUsage::getUsageStatus($userId)
            ];
        });
    }

    /**
     * Track AI usage for a specific operation.
     *
     * @param string $operation
     * @param float $estimatedCost
     * @param int $tokensUsed
     * @param array $metadata
     * @return void
     */
    protected function trackUsage(string $operation, float $estimatedCost = 0.01, int $tokensUsed = 0, array $metadata = []): void
    {
        $userId = auth()->id();

        if (!$userId) {
            // Skip tracking for guest users
            return;
        }

        try {
            AIUsage::create([
                'user_id' => $userId,
                'operation_type' => $operation,
                'provider' => $this->config['default'] ?? 'openai',
                'tokens_used' => $tokensUsed,
                'cost' => $estimatedCost,
                'status' => 'completed',
                'metadata' => $metadata,
            ]);

            Log::info('AI Usage Tracked', [
                'user_id' => $userId,
                'operation' => $operation,
                'cost' => $estimatedCost,
                'tokens' => $tokensUsed
            ]);
        } catch (Exception $e) {
            Log::error('Failed to track AI usage', [
                'user_id' => $userId,
                'operation' => $operation,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get remaining credits for the user.
     *
     * @return float
     */
    protected function getCreditsRemaining(): float
    {
        // This could be stored in database or retrieved from AI provider
        // For now, return a default value
        return 100.00; // $100 worth of credits
    }

    /**
     * Check if AI service is properly configured and available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        try {
            return $this->provider->isAvailable();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get available AI features based on configuration.
     *
     * @return array
     */
    public function getAvailableFeatures(): array
    {
        $features = [];

        if ($this->isContentGenerationEnabled()) {
            $features[] = 'content_generation';
        }

        if ($this->isSeoOptimizationEnabled()) {
            $features[] = 'seo_optimization';
        }

        if ($this->isTranslationEnabled()) {
            $features[] = 'translation';
        }

        if ($this->isSentimentAnalysisEnabled()) {
            $features[] = 'sentiment_analysis';
        }

        if ($this->isContentImprovementEnabled()) {
            $features[] = 'content_improvement';
        }

        if ($this->isAutoTaggingEnabled()) {
            $features[] = 'auto_tagging';
        }

        return $features;
    }

    /**
     * Get supported content tones.
     *
     * @return array
     */
    public function getSupportedTones(): array
    {
        return $this->config['content']['supported_tones'] ?? [];
    }

    /**
     * Get supported content formats.
     *
     * @return array
     */
    public function getSupportedFormats(): array
    {
        return $this->config['content']['supported_formats'] ?? [];
    }

    /**
     * Get supported languages for translation.
     *
     * @return array
     */
    public function getSupportedLanguages(): array
    {
        return $this->config['translation']['supported_languages'] ?? [];
    }

    /**
     * Calculate estimated reading time for content.
     *
     * @param string $content
     * @param int $wordsPerMinute
     * @return int
     */
    protected function calculateReadingTime(string $content, int $wordsPerMinute = 200): int
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    /**
     * Check if content generation is enabled.
     *
     * @return bool
     */
    protected function isContentGenerationEnabled(): bool
    {
        return $this->config['features']['content_generation'] ?? false;
    }

    /**
     * Check if SEO optimization is enabled.
     *
     * @return bool
     */
    protected function isSeoOptimizationEnabled(): bool
    {
        return $this->config['features']['seo_optimization'] ?? false;
    }

    /**
     * Check if translation is enabled.
     *
     * @return bool
     */
    protected function isTranslationEnabled(): bool
    {
        return $this->config['features']['translation'] ?? false;
    }

    /**
     * Check if sentiment analysis is enabled.
     *
     * @return bool
     */
    protected function isSentimentAnalysisEnabled(): bool
    {
        return $this->config['features']['sentiment_analysis'] ?? false;
    }

    /**
     * Check if content improvement is enabled.
     *
     * @return bool
     */
    protected function isContentImprovementEnabled(): bool
    {
        return $this->config['features']['content_improvement'] ?? false;
    }

    /**
     * Check if auto tagging is enabled.
     *
     * @return bool
     */
    protected function isAutoTaggingEnabled(): bool
    {
        return $this->config['features']['auto_tagging'] ?? false;
    }

    /**
     * Create a safe execution wrapper for AI operations.
     *
     * @param callable $operation
     * @param mixed $fallback
     * @return mixed
     */
    protected function safeExecute(callable $operation, $fallback = null)
    {
        try {
            return $operation();
        } catch (Exception $e) {
            Log::warning('AI Operation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $fallback;
        }
    }
}
