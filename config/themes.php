<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Themes
    |--------------------------------------------------------------------------
    |
    | Define the available themes for the CMS. Each theme should have a
    | unique key, display name, and description.
    |
    */

    'available_themes' => [
        'light' => [
            'name' => 'Light Theme',
            'description' => 'Clean, modern light theme with excellent readability',
            'category' => 'professional',
            'colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#6B7280',
                'background' => '#F9FAFB',
                'surface' => '#FFFFFF',
                'text' => '#111827',
            ],
            'preview' => 'light-preview.jpg'
        ],
        'dark' => [
            'name' => 'Dark Theme',
            'description' => 'Sleek dark theme that\'s easy on the eyes',
            'category' => 'professional',
            'colors' => [
                'primary' => '#60A5FA',
                'secondary' => '#9CA3AF',
                'background' => '#111827',
                'surface' => '#1F2937',
                'text' => '#F9FAFB',
            ],
            'preview' => 'dark-preview.jpg'
        ],
        'ocean' => [
            'name' => 'Ocean Breeze',
            'description' => 'Calming blue theme inspired by ocean waves',
            'category' => 'creative',
            'colors' => [
                'primary' => '#0891B2',
                'secondary' => '#0E7490',
                'background' => '#F0F9FF',
                'surface' => '#FFFFFF',
                'text' => '#0C4A6E',
            ],
            'preview' => 'ocean-preview.jpg'
        ],
        'forest' => [
            'name' => 'Forest Green',
            'description' => 'Natural green theme for a calming workspace',
            'category' => 'creative',
            'colors' => [
                'primary' => '#059669',
                'secondary' => '#047857',
                'background' => '#F0FDF4',
                'surface' => '#FFFFFF',
                'text' => '#064E3B',
            ],
            'preview' => 'forest-preview.jpg'
        ],
        'sunset' => [
            'name' => 'Sunset Orange',
            'description' => 'Warm orange theme with energizing vibes',
            'category' => 'creative',
            'colors' => [
                'primary' => '#EA580C',
                'secondary' => '#C2410C',
                'background' => '#FFF7ED',
                'surface' => '#FFFFFF',
                'text' => '#9A3412',
            ],
            'preview' => 'sunset-preview.jpg'
        ],
        'purple-dream' => [
            'name' => 'Purple Dream',
            'description' => 'Rich purple theme for creative professionals',
            'category' => 'creative',
            'colors' => [
                'primary' => '#7C3AED',
                'secondary' => '#6D28D9',
                'background' => '#FAF5FF',
                'surface' => '#FFFFFF',
                'text' => '#581C87',
            ],
            'preview' => 'purple-preview.jpg'
        ],
        'midnight' => [
            'name' => 'Midnight Blue',
            'description' => 'Deep blue theme with optimized accessibility and contrast ratios',
            'category' => 'professional',
            'colors' => [
                'primary' => '#3AB19D',
                'secondary' => '#1E3A8A',
                'background' => '#0F172A',
                'surface' => '#1E293B',
                'text' => '#F1F5F9',
            ],
            'preview' => 'midnight-preview.jpg'
        ],
        'rose-gold' => [
            'name' => 'Rose Gold',
            'description' => 'Elegant pink theme with luxurious feel',
            'category' => 'elegant',
            'colors' => [
                'primary' => '#E11D48',
                'secondary' => '#BE185D',
                'background' => '#FDF2F8',
                'surface' => '#FFFFFF',
                'text' => '#831843',
            ],
            'preview' => 'rose-preview.jpg'
        ],
        'high-contrast' => [
            'name' => 'High Contrast',
            'description' => 'Maximum contrast theme for accessibility',
            'category' => 'accessibility',
            'colors' => [
                'primary' => '#000000',
                'secondary' => '#666666',
                'background' => '#FFFFFF',
                'surface' => '#FFFFFF',
                'text' => '#000000',
            ],
            'preview' => 'contrast-preview.jpg'
        ]
    ],

    'theme_categories' => [
        'professional' => [
            'name' => 'Professional',
            'description' => 'Clean, business-focused themes',
            'icon' => 'briefcase'
        ],
        'creative' => [
            'name' => 'Creative',
            'description' => 'Vibrant, inspiring themes for creative work',
            'icon' => 'palette'
        ],
        'elegant' => [
            'name' => 'Elegant',
            'description' => 'Sophisticated themes with premium feel',
            'icon' => 'sparkles'
        ],
        'accessibility' => [
            'name' => 'Accessibility',
            'description' => 'Themes optimized for accessibility needs',
            'icon' => 'eye'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | The default theme that will be used for new users or when no theme
    | preference is set.
    |
    */

    'default_theme' => env('CMS_DEFAULT_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | Theme Persistence
    |--------------------------------------------------------------------------
    |
    | Whether to persist theme preferences in the database for authenticated
    | users. If disabled, theme preferences will only be stored in the session.
    |
    */

    'persist_user_preferences' => env('CMS_PERSIST_THEME_PREFERENCES', true),

    /*
    |--------------------------------------------------------------------------
    | Theme Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Settings for storing theme preferences in cookies for guest users.
    |
    */

    'cookie' => [
        'name' => 'cms_theme_preference',
        'lifetime' => 60 * 24 * 30, // 30 days in minutes
    ],
];
