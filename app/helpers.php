<?php
use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting($key, $default = null) {
        // Get all settings from cache, or fetch and cache them forever
        $settings = Cache::rememberForever('settings', function () {
            return Setting::all()->pluck('value', 'key');
        });

        return $settings->get($key, $default);
    }
}

if (! function_exists('cache_categories')) {
    function cache_categories() {
        return Cache::remember('categories', 3600, function () { // 1 hour
            return Category::orderBy('name')->get();
        });
    }
}

if (! function_exists('cache_published_posts')) {
    function cache_published_posts($limit = 10) {
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
    function cache_pages_menu() {
        return Cache::remember('pages_menu', 3600, function () { // 1 hour
            return Page::orderBy('order')->get(['id', 'title', 'slug']);
        });
    }
}

if (! function_exists('clear_cms_cache')) {
    function clear_cms_cache() {
        Cache::forget('settings');
        Cache::forget('categories');
        Cache::forget('pages_menu');
        
        // Clear published posts cache (with different limits)
        for ($i = 5; $i <= 50; $i += 5) {
            Cache::forget("published_posts_{$i}");
        }
    }
}