<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $settingsCategories = [
            'general' => [
                'title' => 'General Settings',
                'description' => 'Basic site configuration and information',
                'icon' => 'cog',
                'route' => 'admin.settings.general'
            ],
            'seo' => [
                'title' => 'SEO Settings',
                'description' => 'Search engine optimization and meta tags',
                'icon' => 'search',
                'route' => 'admin.settings.seo'
            ],
            'email' => [
                'title' => 'Email Settings',
                'description' => 'Email configuration and templates',
                'icon' => 'mail',
                'route' => 'admin.settings.email'
            ],
            'footer' => [
                'title' => 'Footer Settings',
                'description' => 'Footer content and links configuration',
                'icon' => 'footer',
                'route' => 'admin.settings.footer'
            ],
            'theme' => [
                'title' => 'Theme & Appearance',
                'description' => 'Theme selection and visual customization',
                'icon' => 'palette',
                'route' => 'admin.settings.theme'
            ],
            'content' => [
                'title' => 'Content Settings',
                'description' => 'Content management and display options',
                'icon' => 'document-text',
                'route' => 'admin.settings.content'
            ],
            'ai' => [
                'title' => 'AI Configuration',
                'description' => 'AI provider settings and content generation',
                'icon' => 'sparkles',
                'route' => 'admin.settings.ai'
            ],
            'integrations' => [
                'title' => 'Integration Settings',
                'description' => 'Third-party service integrations',
                'icon' => 'link',
                'route' => 'admin.settings.integrations'
            ],
            'security' => [
                'title' => 'Security Settings',
                'description' => 'Security and authentication options',
                'icon' => 'shield-check',
                'route' => 'admin.settings.security'
            ],
            'backup' => [
                'title' => 'Backup & Cache',
                'description' => 'System maintenance and cache management',
                'icon' => 'archive-box',
                'route' => 'admin.settings.backup'
            ]
        ];

        return view('admin.settings.index', compact('settingsCategories'));
    }

    public function general()
    {
        return view('admin.settings.general');
    }

    public function storeGeneral(Request $request)
    {
        $rules = [
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:10',
            'maintenance_mode' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|file|mimes:ico,jpeg,png,jpg,gif,svg|max:512',
        ];

        $validated = $request->validate($rules);

        // Handle file uploads with additional security validation
        // Note: Basic file size, extension, and MIME type validation is already handled by Laravel validation rules above
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $result = $this->processImageUpload($logo, 'logo');
            if (is_array($result) && isset($result['error'])) {
                return redirect()->back()->withErrors(['logo' => $result['error']]);
            }
            $validated['logo'] = $result;
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $result = $this->processImageUpload($favicon, 'favicon');
            if (is_array($result) && isset($result['error'])) {
                return redirect()->back()->withErrors(['favicon' => $result['error']]);
            }
            $validated['favicon'] = $result;
        }

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'general.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'General settings saved successfully!');
    }

    public function seo()
    {
        return view('admin.settings.seo');
    }

    public function storeSeo(Request $request)
    {
        $rules = [
            'meta_title_template' => 'nullable|string|max:255',
            'meta_description_template' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'google_search_console' => 'nullable|string|max:100',
            'facebook_app_id' => 'nullable|string|max:50',
            'twitter_handle' => 'nullable|string|max:50',
            'robots_txt' => 'nullable|string',
            'sitemap_enabled' => 'boolean',
            'sitemap_frequency' => 'nullable|string|in:daily,weekly,monthly',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'seo.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'SEO settings saved successfully!');
    }

    public function email()
    {
        return view('admin.settings.email');
    }

    public function storeEmail(Request $request)
    {
        $rules = [
            'contact_email' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email|max:255',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'newsletter_enabled' => 'boolean',
            'newsletter_double_optin' => 'boolean',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $settingKey = 'email.' . $key;
                $settingValue = is_api_key_field($settingKey) ? encrypt_api_key($value) : $value;
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $settingValue]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Email settings saved successfully!');
    }

    public function footer()
    {
        return view('admin.settings.footer');
    }

    public function storeFooter(Request $request)
    {
        $rules = [
            'footer_about_text' => 'nullable|string',
            'copyright_text' => 'nullable|string|max:255',
            'footer_links' => 'nullable|json',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'footer_scripts' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Validate footer_links JSON structure if present
        if (!empty($validated['footer_links'])) {
            // DoS Protection: Enforce maximum payload size (1MB)
            if (strlen($validated['footer_links']) > 1048576) {
                return redirect()->back()->withErrors(['footer_links' => 'Footer links payload is too large. Maximum size is 1MB.']);
            }

            $footerLinks = json_decode($validated['footer_links'], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withErrors(['footer_links' => 'Invalid JSON format for footer links.']);
            }

            if (!is_array($footerLinks)) {
                return redirect()->back()->withErrors(['footer_links' => 'Footer links must be an array of objects.']);
            }

            // DoS Protection: Enforce maximum array count (50 items)
            if (count($footerLinks) > 50) {
                return redirect()->back()->withErrors(['footer_links' => 'Too many footer links. Maximum allowed is 50 items.']);
            }

            // Validate each footer link object structure
            foreach ($footerLinks as $index => $link) {
                if (!is_array($link)) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} must be an object."]);
                }

                // DoS Protection: Validate field lengths first to prevent excessive memory/CPU use
                if (isset($link['title']) && is_string($link['title']) && strlen($link['title']) > 255) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} title is too long. Maximum length is 255 characters."]);
                }

                if (isset($link['url']) && is_string($link['url']) && strlen($link['url']) > 1024) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} URL is too long. Maximum length is 1024 characters."]);
                }

                // DoS Protection: Validate numeric bounds
                if (isset($link['order']) && is_numeric($link['order']) && ($link['order'] < 0 || $link['order'] > 999999)) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} order must be between 0 and 999999."]);
                }

                // Content validation
                if (!isset($link['title']) || !is_string($link['title']) || empty(trim($link['title']))) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} must have a non-empty title."]);
                }

                if (!isset($link['url']) || !is_string($link['url']) || empty(trim($link['url']))) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} must have a non-empty URL."]);
                }

                // Validate URL format
                if (!filter_var($link['url'], FILTER_VALIDATE_URL) && !str_starts_with($link['url'], '/')) {
                    return redirect()->back()->withErrors(['footer_links' => "Footer link at index {$index} must have a valid URL or start with '/' for internal links."]);
                }
            }
        }

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'footer.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Footer settings saved successfully!');
    }

    public function theme()
    {
        $themes = config('themes.available_themes', []);
        return view('admin.settings.theme', compact('themes'));
    }

    public function storeTheme(Request $request)
    {
        $rules = [
            'active_theme' => 'required|string|max:50',
            'custom_css' => 'nullable|string',
            'custom_javascript' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'theme.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Theme settings saved successfully!');
    }

    public function content()
    {
        return view('admin.settings.content');
    }

    public function storeContent(Request $request)
    {
        $rules = [
            'posts_per_page' => 'nullable|integer|min:1|max:100',
            'comments_enabled' => 'boolean',
            'comment_approval' => 'required|in:manual,auto',
            'comments_guest_allowed' => 'boolean',
            'media_max_size' => 'nullable|integer|min:1',
            'allowed_file_types' => 'nullable|string',
            'default_post_template' => 'nullable|string|max:50',
            'default_page_template' => 'nullable|string|max:50',
        ];

        $validated = $request->validate($rules);

        // Convert radio button value to individual settings
        $commentApproval = $validated['comment_approval'];

        // Set moderation and auto-approve based on radio selection
        $validated['comments_moderation'] = ($commentApproval === 'manual');
        $validated['auto_approve_comments'] = ($commentApproval === 'auto');

        // Remove the radio button field as it's not a setting
        unset($validated['comment_approval']);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'content.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Content settings saved successfully!');
    }

    public function ai()
    {
        $aiConfig = config('ai', []);
        return view('admin.settings.ai', compact('aiConfig'));
    }

    public function storeAi(Request $request)
    {
        $rules = [
            'provider' => 'nullable|string|in:openai,gemini,anthropic,local',
            'openai_api_key' => 'nullable|string|max:255',
            'gemini_api_key' => 'nullable|string|max:255',
            'anthropic_api_key' => 'nullable|string|max:255',
            'content_generation' => 'boolean',
            'seo_optimization' => 'boolean',
            'auto_tagging' => 'boolean',
            'content_improvement' => 'boolean',
            'translation' => 'boolean',
            'rate_limiting_enabled' => 'boolean',
            'max_requests_per_hour' => 'nullable|integer|min:1|max:1000',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $settingKey = 'ai.' . $key;
                $settingValue = is_api_key_field($settingKey) ? encrypt_api_key($value) : $value;
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $settingValue]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'AI settings saved successfully!');
    }

    public function integrations()
    {
        return view('admin.settings.integrations');
    }

    public function storeIntegrations(Request $request)
    {
        $rules = [
            'google_maps_api_key' => 'nullable|string|max:255',
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'mailchimp_api_key' => 'nullable|string|max:255',
            'mailchimp_list_id' => 'nullable|string|max:255',
            'stripe_public_key' => 'nullable|string|max:255',
            'stripe_secret_key' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $settingKey = 'integrations.' . $key;
                $settingValue = is_api_key_field($settingKey) ? encrypt_api_key($value) : $value;
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $settingValue]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Integration settings saved successfully!');
    }

    public function security()
    {
        return view('admin.settings.security');
    }

    public function storeSecurity(Request $request)
    {
        $rules = [
            'two_factor_enabled' => 'boolean',
            'password_min_length' => 'nullable|integer|min:6|max:50',
            'password_require_special' => 'boolean',
            'password_require_numbers' => 'boolean',
            'session_timeout' => 'nullable|integer|min:5|max:1440',
            'ip_whitelist' => 'nullable|string',
            'ip_blacklist' => 'nullable|string',
            'captcha_enabled' => 'boolean',
            'failed_login_lockout' => 'boolean',
            'failed_login_attempts' => 'nullable|integer|min:3|max:10',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'security.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Security settings saved successfully!');
    }

    public function backup()
    {
        return view('admin.settings.backup');
    }

    public function storeBackup(Request $request)
    {
        $rules = [
            'backup_enabled' => 'boolean',
            'backup_frequency' => 'nullable|string|in:daily,weekly,monthly',
            'backup_retention_days' => 'nullable|integer|min:1|max:365',
            'auto_optimize_database' => 'boolean',
            'cache_driver' => 'nullable|string|in:file,database,redis',
            'cache_ttl' => 'nullable|integer|min:60|max:86400',
        ];

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(['key' => 'backup.' . $key], ['value' => $value]);
            }
        }

        clear_cms_cache();
        return redirect()->back()->with('success', 'Backup settings saved successfully!');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()->back()->with('success', 'All caches cleared successfully!');
    }

    public function optimizeDatabase()
    {
        Artisan::call('db:optimize');

        return redirect()->back()->with('success', 'Database optimized successfully!');
    }

    // Legacy method for backward compatibility
    public function store(Request $request)
    {
        return $this->storeFooter($request);
    }

    /**
     * Process image upload with additional security validation
     * Performs getimagesize validation for non-SVG and non-ICO files and generates secure filenames
     * Note: Basic file size, extension, and MIME type validation is handled by Laravel validation rules
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type
     * @return string|array Returns file path on success, or array with 'error' key on failure
     */
    private function processImageUpload($file, $type)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = strtolower($file->getMimeType());

        // Skip getimagesize() validation for SVG files and ICO files
        // ICO files often fail getimagesize() validation but are valid favicon files
        if ($extension !== 'svg' && $extension !== 'ico' && $mimeType !== 'image/x-icon' && $mimeType !== 'image/vnd.microsoft.icon') {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                return ['error' => ucfirst($type) . ' appears to be corrupted or not a valid image.'];
            }
        }

        // Generate secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . uniqid() . '_' . time() . '.' . $extension;

        return $file->storeAs('settings', $filename, 'public');
    }
}
