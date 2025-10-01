<?php
use App\Models\Setting;
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