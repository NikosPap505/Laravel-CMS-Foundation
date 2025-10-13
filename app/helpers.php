<?php

use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        // Get all settings from cache, or fetch and cache them forever
        $settings = Cache::rememberForever('settings', function () {
            return Setting::all()->pluck('value', 'key');
        });

        $value = $settings->get($key, $default);

        // Decrypt API keys when retrieving
        if (is_api_key_field($key) && $value) {
            return decrypt_api_key($value);
        }

        return $value;
    }
}

if (! function_exists('cache_categories')) {
    function cache_categories()
    {
        return Cache::remember('categories', 3600, function () { // 1 hour
            return Category::orderBy('name')->get();
        });
    }
}

if (! function_exists('cache_published_posts')) {
    function cache_published_posts($limit = 10)
    {
        return Cache::remember("published_posts_{$limit}", 1800, function () use ($limit) { // 30 minutes
            return Post::with(['category', 'featuredImage'])
                ->published()
                ->latest('published_at')
                ->limit($limit)
                ->get();
        });
    }
}

if (! function_exists('cache_pages_menu')) {
    function cache_pages_menu()
    {
        return Cache::remember('pages_menu', 3600, function () { // 1 hour
            return Page::orderBy('order')->get(['id', 'title', 'slug']);
        });
    }
}

if (! function_exists('clear_cms_cache')) {
    function clear_cms_cache()
    {
        Cache::forget('settings');
        Cache::forget('categories');
        Cache::forget('pages_menu');

        // Clear published posts cache (with different limits)
        for ($i = 5; $i <= 50; $i += 5) {
            Cache::forget("published_posts_{$i}");
        }
    }
}

if (! function_exists('encrypt_api_key')) {
    /**
     * Encrypt an API key for storage
     */
    function encrypt_api_key($value)
    {
        if (empty($value)) {
            return null;
        }
        return Crypt::encryptString($value);
    }
}

if (! function_exists('decrypt_api_key')) {
    /**
     * Decrypt an API key from storage
     */
    function decrypt_api_key($encryptedValue)
    {
        if (empty($encryptedValue)) {
            return null;
        }
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (\Exception $e) {
            // If decryption fails, return original value (might be unencrypted legacy data)
            return $encryptedValue;
        }
    }
}

if (! function_exists('is_api_key_field')) {
    /**
     * Check if a setting key is an API key that should be encrypted
     */
    function is_api_key_field($key)
    {
        $apiKeyFields = [
            'ai.openai_api_key',
            'ai.gemini_api_key',
            'ai.anthropic_api_key',
            'integrations.google_maps_api_key',
            'integrations.recaptcha_site_key',
            'integrations.recaptcha_secret_key',
            'integrations.mailchimp_api_key',
            'integrations.stripe_public_key',
            'integrations.stripe_secret_key',
            'email.smtp_username',
            'email.smtp_password',
        ];

        return in_array($key, $apiKeyFields);
    }
}
