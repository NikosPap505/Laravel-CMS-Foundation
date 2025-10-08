<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used by the
    | application. You may change this default provider to any of the
    | supported providers: "openai", "anthropic", "local"
    |
    */

    'default' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the AI providers for your application. Each
    | provider has its own configuration options.
    |
    */

    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'organization' => env('OPENAI_ORGANIZATION'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2000),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
            'timeout' => env('OPENAI_TIMEOUT', 30),
        ],

        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'models/gemini-2.0-flash'),
            'max_tokens' => env('GEMINI_MAX_TOKENS', 2048),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
            'top_p' => env('GEMINI_TOP_P', 0.8),
            'top_k' => env('GEMINI_TOP_K', 40),
            'timeout' => env('GEMINI_TIMEOUT', 30),
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
            'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 2000),
            'temperature' => env('ANTHROPIC_TEMPERATURE', 0.7),
        ],

        'local' => [
            'endpoint' => env('LOCAL_AI_ENDPOINT', 'http://localhost:8080'),
            'model' => env('LOCAL_AI_MODEL', 'llama2'),
            'timeout' => env('LOCAL_AI_TIMEOUT', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Generation Settings
    |--------------------------------------------------------------------------
    |
    | These options control various aspects of AI-powered content generation.
    |
    */

    'content' => [
        'max_title_length' => 60,
        'max_excerpt_length' => 160,
        'max_meta_description_length' => 155,
        'default_tone' => 'professional',
        'supported_tones' => [
            'professional',
            'casual',
            'friendly',
            'authoritative',
            'conversational',
            'technical',
            'creative',
        ],
        'supported_formats' => [
            'blog_post',
            'article',
            'product_description',
            'meta_description',
            'social_media_post',
            'email_newsletter',
            'press_release',
        ],
        'auto_generate_images' => env('AI_AUTO_GENERATE_IMAGES', false),
        'auto_optimize_seo' => env('AI_AUTO_OPTIMIZE_SEO', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Optimization Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for AI-powered SEO optimization features.
    |
    */

    'seo' => [
        'auto_generate_meta_tags' => true,
        'auto_generate_alt_text' => true,
        'keyword_density_target' => 2.0, // Percentage
        'readability_target' => 'grade_8', // Reading level
        'schema_markup_auto' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Settings
    |--------------------------------------------------------------------------
    |
    | Settings for AI-powered content translation.
    |
    */

    'translation' => [
        'enabled' => env('AI_TRANSLATION_ENABLED', false),
        'supported_languages' => [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'nl' => 'Dutch',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'zh' => 'Chinese',
        ],
        'preserve_formatting' => true,
        'preserve_html_tags' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for AI API calls to prevent excessive usage
    | and manage costs.
    |
    */

    'rate_limiting' => [
        'enabled' => env('AI_RATE_LIMITING_ENABLED', true),
        'max_requests_per_minute' => env('AI_MAX_REQUESTS_PER_MINUTE', 10),
        'max_requests_per_hour' => env('AI_MAX_REQUESTS_PER_HOUR', 100),
        'max_requests_per_day' => env('AI_MAX_REQUESTS_PER_DAY', 500),
        'cost_limit_per_day' => env('AI_COST_LIMIT_PER_DAY', 10.00), // USD
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Configure caching for AI responses to improve performance and reduce
    | API costs by avoiding duplicate requests.
    |
    */

    'caching' => [
        'enabled' => env('AI_CACHING_ENABLED', true),
        'ttl' => env('AI_CACHE_TTL', 3600), // Cache for 1 hour
        'store' => env('AI_CACHE_STORE', 'default'),
        'prefix' => 'ai_content_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Safety
    |--------------------------------------------------------------------------
    |
    | Configure content moderation and safety checks for AI-generated content.
    |
    */

    'safety' => [
        'content_filtering' => env('AI_CONTENT_FILTERING', true),
        'profanity_check' => env('AI_PROFANITY_CHECK', true),
        'spam_detection' => env('AI_SPAM_DETECTION', true),
        'fact_checking' => env('AI_FACT_CHECKING', false),
        'plagiarism_check' => env('AI_PLAGIARISM_CHECK', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics & Monitoring
    |--------------------------------------------------------------------------
    |
    | Track AI usage, costs, and performance metrics.
    |
    */

    'analytics' => [
        'track_usage' => env('AI_TRACK_USAGE', true),
        'track_costs' => env('AI_TRACK_COSTS', true),
        'track_performance' => env('AI_TRACK_PERFORMANCE', true),
        'log_requests' => env('AI_LOG_REQUESTS', true),
        'log_errors' => env('AI_LOG_ERRORS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific AI features.
    |
    */

    'features' => [
        'content_generation' => env('AI_CONTENT_GENERATION', true),
        'seo_optimization' => env('AI_SEO_OPTIMIZATION', true),
        'translation' => env('AI_TRANSLATION', false),
        'image_generation' => env('AI_IMAGE_GENERATION', false),
        'content_improvement' => env('AI_CONTENT_IMPROVEMENT', true),
        'auto_tagging' => env('AI_AUTO_TAGGING', true),
        'sentiment_analysis' => env('AI_SENTIMENT_ANALYSIS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Prompts & Templates
    |--------------------------------------------------------------------------
    |
    | Default prompts and templates for different content types.
    |
    */

    'prompts' => [
        'blog_post' => [
            'system' => 'You are a professional content writer specializing in creating engaging, SEO-optimized blog posts.',
            'template' => 'Write a {tone} blog post about "{topic}" that is approximately {word_count} words long. Include an engaging introduction, well-structured body paragraphs with subheadings, and a compelling conclusion. Focus on providing value to readers interested in {target_audience}.',
        ],

        'meta_description' => [
            'system' => 'You are an SEO specialist focused on creating compelling meta descriptions.',
            'template' => 'Create a compelling meta description for a {content_type} titled "{title}". The description should be under 155 characters, include relevant keywords, and encourage clicks while accurately describing the content.',
        ],

        'product_description' => [
            'system' => 'You are a skilled copywriter specializing in persuasive product descriptions.',
            'template' => 'Write a {tone} product description for "{product_name}". Highlight key features, benefits, and what makes this product unique. Target audience: {target_audience}. Keep it concise and persuasive.',
        ],
    ],

];