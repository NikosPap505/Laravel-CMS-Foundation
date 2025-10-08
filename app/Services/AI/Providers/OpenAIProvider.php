<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use OpenAI\Client;
use OpenAI\Factory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class OpenAIProvider implements AIProviderInterface
{
    protected Client $client;
    protected array $config;
    protected array $usageStats = [];

    public function __construct()
    {
        $this->config = config('ai.providers.openai');
        
        if (!$this->config['api_key']) {
            throw new Exception('OpenAI API key not configured');
        }

        $this->client = Factory::withApiKey($this->config['api_key'])
            ->withOrganization($this->config['organization'] ?? null)
            ->withHttpClient(new \GuzzleHttp\Client(['timeout' => $this->config['timeout'] ?? 30]))
            ->make();
    }

    public function generateContent(string $prompt, array $parameters = []): string
    {
        if (!$this->checkRateLimit()) {
            throw new Exception('Rate limit exceeded for AI requests');
        }

        $cacheKey = 'ai_content_' . md5($prompt . serialize($parameters));
        
        if (config('ai.caching.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        try {
            $response = $this->client->chat()->create([
                'model' => $this->config['model'],
                'messages' => [
                    ['role' => 'system', 'content' => $parameters['system'] ?? 'You are a helpful AI assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => $parameters['max_tokens'] ?? $this->config['max_tokens'],
                'temperature' => $parameters['temperature'] ?? $this->config['temperature'],
                'presence_penalty' => $parameters['presence_penalty'] ?? 0,
                'frequency_penalty' => $parameters['frequency_penalty'] ?? 0,
            ]);

            $content = $response->choices[0]->message->content;

            // Track usage
            $this->trackUsage([
                'prompt_tokens' => $response->usage->promptTokens,
                'completion_tokens' => $response->usage->completionTokens,
                'total_tokens' => $response->usage->totalTokens,
                'model' => $this->config['model'],
            ]);

            // Cache the result
            if (config('ai.caching.enabled')) {
                Cache::put($cacheKey, $content, config('ai.caching.ttl'));
            }

            return $content;

        } catch (Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            throw new Exception('Failed to generate content: ' . $e->getMessage());
        }
    }

    public function generateBlogPost(string $topic, array $options = []): array
    {
        $tone = $options['tone'] ?? config('ai.content.default_tone');
        $wordCount = $options['word_count'] ?? 800;
        $targetAudience = $options['target_audience'] ?? 'general readers';
        
        $systemPrompt = config('ai.prompts.blog_post.system');
        $template = config('ai.prompts.blog_post.template');
        
        $prompt = str_replace([
            '{tone}',
            '{topic}',
            '{word_count}',
            '{target_audience}'
        ], [
            $tone,
            $topic,
            $wordCount,
            $targetAudience
        ], $template);

        $content = $this->generateContent($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 3000,
            'temperature' => 0.8
        ]);

        // Generate additional elements
        $title = $this->generateTitleSuggestions($topic, ['count' => 1])[0];
        $excerpt = $this->generateExcerpt($content);
        $metaDescription = $this->generateMetaDescription($title, $content);
        $tags = $this->generateTags($content);

        return [
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'meta_description' => $metaDescription,
            'tags' => $tags,
        ];
    }

    public function generateMetaDescription(string $title, string $content = '', array $options = []): string
    {
        $maxLength = $options['max_length'] ?? config('ai.content.max_meta_description_length');
        
        $systemPrompt = config('ai.prompts.meta_description.system');
        $template = config('ai.prompts.meta_description.template');
        
        $prompt = str_replace([
            '{content_type}',
            '{title}',
        ], [
            $options['content_type'] ?? 'article',
            $title,
        ], $template);

        if ($content) {
            $prompt .= "\n\nContent summary: " . substr($content, 0, 500) . "...";
        }

        $prompt .= "\n\nKeep it under {$maxLength} characters and make it compelling for search results.";

        return $this->generateContent($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 100,
            'temperature' => 0.7
        ]);
    }

    public function generateTitleSuggestions(string $topic, array $options = []): array
    {
        $count = $options['count'] ?? 5;
        $tone = $options['tone'] ?? 'engaging';
        
        $prompt = "Generate {$count} {$tone} blog post titles about '{$topic}'. Make them SEO-friendly, compelling, and under 60 characters each. Return only the titles, one per line.";

        $response = $this->generateContent($prompt, [
            'system' => 'You are an expert copywriter specializing in creating compelling, SEO-optimized headlines.',
            'max_tokens' => 300,
            'temperature' => 0.9
        ]);

        return array_filter(array_map('trim', explode("\n", $response)));
    }

    public function improveContent(string $content, array $options = []): array
    {
        $improvements = [];
        
        // Grammar and readability check
        $grammarPrompt = "Review and improve this content for grammar, readability, and flow. Return only the improved version:\n\n" . $content;
        $improvements['content'] = $this->generateContent($grammarPrompt, [
            'system' => 'You are a professional editor focused on improving content clarity, grammar, and readability.',
            'temperature' => 0.3
        ]);

        // SEO suggestions
        $seoPrompt = "Analyze this content and provide 5 specific SEO improvement suggestions:\n\n" . substr($content, 0, 1000);
        $improvements['seo_suggestions'] = $this->generateContent($seoPrompt, [
            'system' => 'You are an SEO expert. Provide actionable, specific recommendations.',
            'temperature' => 0.5
        ]);

        // Readability score
        $improvements['readability_score'] = $this->calculateReadabilityScore($content);

        return $improvements;
    }

    public function generateTags(string $content, array $options = []): array
    {
        $maxTags = $options['max_tags'] ?? 10;
        
        $prompt = "Analyze this content and generate up to {$maxTags} relevant tags/keywords. Focus on SEO-friendly, searchable terms. Return only the tags, separated by commas:\n\n" . substr($content, 0, 1500);

        $response = $this->generateContent($prompt, [
            'system' => 'You are an SEO specialist focused on keyword research and content tagging.',
            'max_tokens' => 200,
            'temperature' => 0.6
        ]);

        return array_map('trim', explode(',', $response));
    }

    public function generateImageAltText(string $imageUrl, string $context = ''): string
    {
        // Note: This is a placeholder. For actual image analysis, you'd need GPT-4 Vision
        $prompt = "Generate descriptive alt text for an image";
        if ($context) {
            $prompt .= " in the context of: {$context}";
        }
        $prompt .= ". The image URL is: {$imageUrl}. Make it descriptive but concise (under 125 characters).";

        return $this->generateContent($prompt, [
            'system' => 'You are an accessibility expert creating descriptive alt text for images.',
            'max_tokens' => 50,
            'temperature' => 0.5
        ]);
    }

    public function translateContent(string $content, string $targetLanguage, array $options = []): string
    {
        $preserveFormatting = $options['preserve_formatting'] ?? true;
        $preserveHtml = $options['preserve_html_tags'] ?? true;
        
        $prompt = "Translate the following content to {$targetLanguage}";
        if ($preserveFormatting) {
            $prompt .= ". Preserve all formatting";
        }
        if ($preserveHtml) {
            $prompt .= " and HTML tags";
        }
        $prompt .= ":\n\n{$content}";

        return $this->generateContent($prompt, [
            'system' => 'You are a professional translator. Maintain the original meaning and tone while adapting for the target language and culture.',
            'temperature' => 0.3
        ]);
    }

    public function analyzeSentiment(string $content): array
    {
        $prompt = "Analyze the sentiment of this content. Provide a JSON response with sentiment (positive/negative/neutral), confidence score (0-100), and key emotional indicators:\n\n" . substr($content, 0, 1000);

        $response = $this->generateContent($prompt, [
            'system' => 'You are a sentiment analysis expert. Always respond with valid JSON.',
            'temperature' => 0.2
        ]);

        try {
            return json_decode($response, true) ?? [
                'sentiment' => 'neutral',
                'confidence' => 50,
                'emotional_indicators' => []
            ];
        } catch (Exception $e) {
            return [
                'sentiment' => 'neutral',
                'confidence' => 50,
                'emotional_indicators' => [],
                'error' => 'Failed to parse sentiment analysis'
            ];
        }
    }

    public function generateProductDescription(string $productName, array $features = [], array $options = []): string
    {
        $tone = $options['tone'] ?? 'persuasive';
        $targetAudience = $options['target_audience'] ?? 'potential customers';
        
        $systemPrompt = config('ai.prompts.product_description.system');
        $template = config('ai.prompts.product_description.template');
        
        $prompt = str_replace([
            '{tone}',
            '{product_name}',
            '{target_audience}'
        ], [
            $tone,
            $productName,
            $targetAudience
        ], $template);

        if (!empty($features)) {
            $prompt .= "\n\nKey features: " . implode(', ', $features);
        }

        return $this->generateContent($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 500,
            'temperature' => 0.8
        ]);
    }

    public function generateSocialMediaPost(string $content, string $platform = 'twitter', array $options = []): string
    {
        $characterLimits = [
            'twitter' => 280,
            'facebook' => 400,
            'linkedin' => 300,
            'instagram' => 300
        ];
        
        $limit = $characterLimits[$platform] ?? 280;
        $tone = $options['tone'] ?? 'engaging';
        
        $prompt = "Create a {$tone} {$platform} post (under {$limit} characters) based on this content. Include relevant hashtags:\n\n" . substr($content, 0, 500);

        return $this->generateContent($prompt, [
            'system' => "You are a social media expert specializing in {$platform} content creation.",
            'max_tokens' => 150,
            'temperature' => 0.8
        ]);
    }

    public function isAvailable(): bool
    {
        return !empty($this->config['api_key']);
    }

    public function getConfig(): array
    {
        return array_merge($this->config, ['api_key' => '[HIDDEN]']);
    }

    public function getUsageStats(): array
    {
        return $this->usageStats;
    }

    protected function generateExcerpt(string $content, int $maxLength = 160): string
    {
        $prompt = "Create a compelling excerpt ({$maxLength} characters max) from this content that will make people want to read more:\n\n" . substr($content, 0, 800);

        return $this->generateContent($prompt, [
            'system' => 'You are an expert at creating engaging content excerpts and summaries.',
            'max_tokens' => 100,
            'temperature' => 0.7
        ]);
    }

    protected function checkRateLimit(): bool
    {
        if (!config('ai.rate_limiting.enabled')) {
            return true;
        }

        $key = 'ai_rate_limit:' . request()->ip();
        $maxRequests = config('ai.rate_limiting.max_requests_per_minute');

        return RateLimiter::attempt($key, $maxRequests, function() {
            return true;
        }, 60);
    }

    protected function trackUsage(array $usage): void
    {
        if (!config('ai.analytics.track_usage')) {
            return;
        }

        $this->usageStats[] = array_merge($usage, [
            'timestamp' => now(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        // Log usage for analytics
        if (config('ai.analytics.log_requests')) {
            Log::info('OpenAI Usage', $usage);
        }
    }

    protected function calculateReadabilityScore(string $content): array
    {
        // Simple readability metrics
        $sentences = preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($content);
        $characters = strlen(strip_tags($content));
        
        $avgWordsPerSentence = count($sentences) > 0 ? $words / count($sentences) : 0;
        $avgCharsPerWord = $words > 0 ? $characters / $words : 0;
        
        // Simple scoring algorithm (higher is better readability)
        $score = 100 - ($avgWordsPerSentence * 2) - ($avgCharsPerWord * 5);
        $score = max(0, min(100, $score));
        
        return [
            'score' => round($score, 1),
            'grade_level' => $this->getGradeLevel($score),
            'sentences' => count($sentences),
            'words' => $words,
            'avg_words_per_sentence' => round($avgWordsPerSentence, 1),
            'avg_chars_per_word' => round($avgCharsPerWord, 1),
        ];
    }

    protected function getGradeLevel(float $score): string
    {
        if ($score >= 90) return 'Elementary School';
        if ($score >= 80) return 'Middle School';
        if ($score >= 70) return 'High School';
        if ($score >= 60) return 'College';
        if ($score >= 50) return 'Graduate';
        return 'Professional';
    }
}