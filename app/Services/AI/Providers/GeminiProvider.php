<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use Gemini;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class GeminiProvider implements AIProviderInterface
{
    protected $client;
    protected array $config;
    protected array $usageStats = [];

    public function __construct()
    {
        $this->config = config('ai.providers.gemini');
        
        if (!$this->config['api_key']) {
            throw new Exception('Gemini API key not configured');
        }

        $this->client = Gemini::client($this->config['api_key']);
    }

    public function generateContent(string $prompt, array $parameters = []): string
    {
        if (!$this->checkRateLimit()) {
            throw new Exception('Rate limit exceeded for AI requests');
        }

        $cacheKey = 'ai_gemini_' . md5($prompt . serialize($parameters));
        
        if (config('ai.caching.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        try {
            $resolvedModelName = Cache::get('ai_gemini_working_model') ?: ($this->config['model'] ?? 'models/gemini-2.5-flash');
            $candidates = array_unique([
                $resolvedModelName,
                'models/gemini-2.5-flash',
                'models/gemini-2.5-pro',
                'models/gemini-2.0-flash',
                'models/gemini-flash-latest',
                'models/gemini-pro-latest',
            ]);
            
            $model = null;
            foreach ($candidates as $name) {
                try {
                    $testModel = $this->client->generativeModel($name);
                    // Lightweight validation to ensure this model supports generation on this account
                    $testModel->countTokens('ping');
                    $model = $testModel;
                    $resolvedModelName = $name;
                    Cache::put('ai_gemini_working_model', $name, now()->addDay());
                    break;
                } catch (\Throwable $ex) {
                    // Try next candidate
                    continue;
                }
            }
            
            if (!$model) {
                throw new Exception('No supported Gemini model found for your account. Please set GEMINI_MODEL to one of the available models listed in Google AI Studio.');
            }
            
            $systemInstruction = $parameters['system'] ?? 'You are a helpful AI assistant specialized in content creation and optimization.';
            $fullPrompt = $systemInstruction . "\n\n" . $prompt;

            $response = $model->generateContent($fullPrompt);

            $content = $response->text();

            // Track usage
            $this->trackUsage([
                'model' => $resolvedModelName,
                'prompt_characters' => strlen($fullPrompt),
                'response_characters' => strlen($content),
                'cost_estimate' => $this->calculateCost(strlen($fullPrompt), strlen($content)),
            ]);

            // Cache the result
            if (config('ai.caching.enabled')) {
                Cache::put($cacheKey, $content, config('ai.caching.ttl'));
            }

            return $content;

        } catch (Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new Exception('Failed to generate content: ' . $e->getMessage());
        }
    }

    public function generateBlogPost(string $topic, array $options = []): array
    {
        $tone = $options['tone'] ?? config('ai.content.default_tone');
        $wordCount = $options['word_count'] ?? 800;
        $targetAudience = $options['target_audience'] ?? 'general readers';
        
        $systemPrompt = 'You are a professional content writer specializing in creating engaging, SEO-optimized blog posts. You write clear, informative, and well-structured content.';
        
        $prompt = "Write a {$tone} blog post about \"{$topic}\" that is approximately {$wordCount} words long. 

Structure the post with:
1. An engaging introduction that hooks the reader
2. Well-organized body paragraphs with clear subheadings (use ## for subheadings)
3. A compelling conclusion that summarizes key points
4. Focus on providing value to {$targetAudience}

Make the content informative, engaging, and optimized for readability.";

        $content = $this->generateContent($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 3000,
            'temperature' => 0.8
        ]);

        // Generate additional elements
        $title = $this->generateTitleSuggestions($topic, ['count' => 1])[0] ?? ucwords($topic);
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
        
        $systemPrompt = 'You are an SEO specialist focused on creating compelling meta descriptions that improve click-through rates from search results.';
        
        $contentType = $options['content_type'] ?? 'article';
        $prompt = "Create a compelling meta description for a {$contentType} titled \"{$title}\".";

        if ($content) {
            $contentSummary = substr(strip_tags($content), 0, 500);
            $prompt .= " Here's a summary of the content: {$contentSummary}";
        }

        $prompt .= "\n\nRequirements:
- Under {$maxLength} characters
- Include relevant keywords naturally
- Make it compelling to encourage clicks
- Accurately describe the content
- Write in an engaging tone

Return only the meta description, nothing else.";

        return trim($this->generateContent($prompt, [
            'system' => $systemPrompt,
            'max_tokens' => 100,
            'temperature' => 0.7
        ]));
    }

    public function generateTitleSuggestions(string $topic, array $options = []): array
    {
        $count = $options['count'] ?? 5;
        $tone = $options['tone'] ?? 'engaging';
        
        $prompt = "Generate {$count} {$tone} blog post titles about '{$topic}'. 

Requirements:
- Make them SEO-friendly and compelling
- Keep each title under 60 characters
- Make them click-worthy but not clickbait
- Include relevant keywords naturally
- Vary the title structures

Return only the titles, one per line, without numbers or bullets.";

        $response = $this->generateContent($prompt, [
            'system' => 'You are an expert copywriter specializing in creating compelling, SEO-optimized headlines that drive engagement.',
            'max_tokens' => 300,
            'temperature' => 0.9
        ]);

        $titles = array_filter(array_map('trim', explode("\n", $response)));
        
        // Clean up any numbering that might have been added
        $titles = array_map(function($title) {
            return preg_replace('/^\d+[\.\)\-\s]*/', '', $title);
        }, $titles);

        return array_values(array_filter($titles));
    }

    public function improveContent(string $content, array $options = []): array
    {
        $improvements = [];
        
        // Grammar and readability check
        $grammarPrompt = "Review and improve this content for grammar, readability, and flow. Make it more engaging and easier to read while preserving the original meaning and key information.

Content to improve:
{$content}

Return only the improved version without any explanations or comments.";

        $improvements['content'] = $this->generateContent($grammarPrompt, [
            'system' => 'You are a professional editor focused on improving content clarity, grammar, and readability. You make text more engaging while maintaining accuracy.',
            'temperature' => 0.3
        ]);

        // SEO suggestions
        $seoPrompt = "Analyze this content and provide 5 specific, actionable SEO improvement suggestions:

{$content}

Focus on:
1. Keyword optimization opportunities
2. Content structure improvements
3. Meta information suggestions
4. Internal linking opportunities
5. User experience enhancements

Provide clear, specific recommendations that can be implemented immediately.";

        $improvements['seo_suggestions'] = $this->generateContent($seoPrompt, [
            'system' => 'You are an SEO expert with deep knowledge of search engine optimization best practices. Provide actionable, specific recommendations.',
            'temperature' => 0.5
        ]);

        // Readability score
        $improvements['readability_score'] = $this->calculateReadabilityScore($content);

        return $improvements;
    }

    public function generateTags(string $content, array $options = []): array
    {
        $maxTags = $options['max_tags'] ?? 10;
        
        $contentSummary = substr(strip_tags($content), 0, 1500);
        $prompt = "Analyze this content and generate up to {$maxTags} relevant SEO tags/keywords.

Content:
{$contentSummary}

Requirements:
- Focus on searchable, relevant terms
- Include a mix of broad and specific keywords
- Consider what users might search for
- Make them SEO-friendly
- Include both primary and secondary keywords

Return only the tags, separated by commas, without any explanations.";

        $response = $this->generateContent($prompt, [
            'system' => 'You are an SEO specialist focused on keyword research and content tagging for maximum search visibility.',
            'max_tokens' => 200,
            'temperature' => 0.6
        ]);

        $tags = array_map('trim', explode(',', $response));
        
        // Clean and filter tags
        $tags = array_filter($tags, function($tag) {
            return strlen($tag) > 2 && strlen($tag) < 50;
        });

        return array_values(array_slice($tags, 0, $maxTags));
    }

    public function generateImageAltText(string $imageUrl, string $context = ''): string
    {
        // Note: Gemini Pro Vision would be needed for actual image analysis
        // For now, we'll generate contextual alt text
        $prompt = "Generate descriptive alt text for an image";
        if ($context) {
            $prompt .= " in the context of: {$context}";
        }
        $prompt .= ". The image is located at: {$imageUrl}. 

Create concise, descriptive alt text that:
- Describes what the image shows
- Is under 125 characters
- Includes relevant context
- Is helpful for screen readers

Return only the alt text, nothing else.";

        return trim($this->generateContent($prompt, [
            'system' => 'You are an accessibility expert creating descriptive alt text for images that helps visually impaired users understand content.',
            'max_tokens' => 50,
            'temperature' => 0.5
        ]));
    }

    public function translateContent(string $content, string $targetLanguage, array $options = []): string
    {
        $preserveFormatting = $options['preserve_formatting'] ?? true;
        $preserveHtml = $options['preserve_html_tags'] ?? true;
        
        $prompt = "Translate the following content to {$targetLanguage}.

Requirements:
- Maintain the original meaning and tone
- Adapt for the target language and culture
- Keep the content natural and fluent";

        if ($preserveFormatting) {
            $prompt .= "\n- Preserve all formatting and structure";
        }
        if ($preserveHtml) {
            $prompt .= "\n- Keep all HTML tags intact";
        }

        $prompt .= "\n\nContent to translate:\n{$content}";

        return $this->generateContent($prompt, [
            'system' => 'You are a professional translator with expertise in maintaining meaning, tone, and cultural nuances across languages.',
            'temperature' => 0.3
        ]);
    }

    public function analyzeSentiment(string $content): array
    {
        $contentSample = substr($content, 0, 1000);
        $prompt = "Analyze the sentiment of this content and provide a detailed analysis.

Content:
{$contentSample}

Provide your analysis in this exact JSON format:
{
    \"sentiment\": \"positive/negative/neutral\",
    \"confidence\": 85,
    \"emotional_indicators\": [\"keyword1\", \"keyword2\", \"keyword3\"],
    \"tone\": \"description of overall tone\",
    \"summary\": \"brief explanation of the sentiment analysis\"
}

Return only valid JSON, no additional text or explanations.";

        $response = $this->generateContent($prompt, [
            'system' => 'You are a sentiment analysis expert. Always respond with valid JSON format exactly as requested.',
            'temperature' => 0.2
        ]);

        try {
            $analysis = json_decode($response, true);
            
            // Validate response structure
            if (!isset($analysis['sentiment']) || !isset($analysis['confidence'])) {
                throw new Exception('Invalid response format');
            }
            
            return $analysis;
        } catch (Exception $e) {
            return [
                'sentiment' => 'neutral',
                'confidence' => 50,
                'emotional_indicators' => [],
                'tone' => 'neutral',
                'summary' => 'Analysis unavailable',
                'error' => 'Failed to parse sentiment analysis'
            ];
        }
    }

    public function generateProductDescription(string $productName, array $features = [], array $options = []): string
    {
        $tone = $options['tone'] ?? 'persuasive';
        $targetAudience = $options['target_audience'] ?? 'potential customers';
        
        $prompt = "Write a {$tone} product description for \"{$productName}\" targeting {$targetAudience}.";

        if (!empty($features)) {
            $prompt .= "\n\nKey features to highlight:\n" . implode("\n", array_map(fn($f) => "- {$f}", $features));
        }

        $prompt .= "\n\nRequirements:
- Make it compelling and persuasive
- Highlight unique selling points
- Include benefits, not just features
- Keep it concise but informative
- Use engaging language that converts
- Focus on how it solves customer problems";

        return $this->generateContent($prompt, [
            'system' => 'You are a skilled copywriter specializing in persuasive product descriptions that drive conversions.',
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
        
        $contentSummary = substr($content, 0, 500);
        $prompt = "Create a {$tone} {$platform} post based on this content (under {$limit} characters):

Source content:
{$contentSummary}

Requirements:
- Stay under {$limit} characters
- Make it platform-appropriate for {$platform}
- Include relevant hashtags (2-3 max)
- Make it engaging and shareable
- Maintain the key message from the source content
- Use {$tone} tone

Return only the social media post, nothing else.";

        return trim($this->generateContent($prompt, [
            'system' => "You are a social media expert specializing in creating engaging {$platform} content that drives engagement.",
            'max_tokens' => 150,
            'temperature' => 0.8
        ]));
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
        $prompt = "Create a compelling excerpt from this content that will make people want to read more. Keep it under {$maxLength} characters and make it engaging.

Content:
" . substr($content, 0, 800) . "

Return only the excerpt, nothing else.";

        return trim($this->generateContent($prompt, [
            'system' => 'You are an expert at creating engaging content excerpts and summaries that capture attention.',
            'max_tokens' => 100,
            'temperature' => 0.7
        ]));
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
            Log::info('Gemini Usage', $usage);
        }
    }

    protected function calculateCost(int $inputChars, int $outputChars): float
    {
        // Gemini pricing: $0.00025 per 1K input characters, $0.0005 per 1K output characters
        $inputCost = ($inputChars / 1000) * 0.00025;
        $outputCost = ($outputChars / 1000) * 0.0005;
        
        return round($inputCost + $outputCost, 6);
    }

    protected function calculateReadabilityScore(string $content): array
    {
        // Simple readability metrics
        $sentences = preg_split('/[.!?]+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count($content);
        $characters = strlen(strip_tags($content));
        
        $avgWordsPerSentence = count($sentences) > 0 ? $words / count($sentences) : 0;
        $avgCharsPerWord = $words > 0 ? $characters / $words : 0;
        
        // Flesch Reading Ease approximation
        $score = 206.835 - (1.015 * $avgWordsPerSentence) - (84.6 * ($avgCharsPerWord / 4.7));
        $score = max(0, min(100, $score));
        
        return [
            'score' => round($score, 1),
            'grade_level' => $this->getGradeLevel($score),
            'sentences' => count($sentences),
            'words' => $words,
            'characters' => $characters,
            'avg_words_per_sentence' => round($avgWordsPerSentence, 1),
            'avg_chars_per_word' => round($avgCharsPerWord, 1),
            'reading_time' => max(1, ceil($words / 200)), // 200 words per minute
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