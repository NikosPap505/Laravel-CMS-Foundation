<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Integration Hub Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Integration Hub system.
    | You can configure various integration providers and their settings here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Integration Settings
    |--------------------------------------------------------------------------
    */
    'default' => [
        'timeout' => 30,
        'retry_attempts' => 3,
        'retry_delay' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Providers
    |--------------------------------------------------------------------------
    |
    | Configure the available integration providers and their settings.
    |
    */
    'providers' => [
        'google_analytics_4' => [
            'enabled' => env('GOOGLE_ANALYTICS_4_ENABLED', true),
            'rate_limit' => [
                'requests_per_minute' => 10,
                'requests_per_hour' => 100,
            ],
        ],

        'google_tag_manager' => [
            'enabled' => env('GOOGLE_TAG_MANAGER_ENABLED', true),
            'rate_limit' => [
                'requests_per_minute' => 10,
                'requests_per_hour' => 100,
            ],
        ],

        'mailchimp' => [
            'enabled' => env('MAILCHIMP_ENABLED', true),
            'webhook_secret' => env('MAILCHIMP_WEBHOOK_SECRET'),
            'rate_limit' => [
                'requests_per_minute' => 10,
                'requests_per_hour' => 100,
            ],
        ],

        'instagram' => [
            'enabled' => env('INSTAGRAM_ENABLED', true),
            'rate_limit' => [
                'requests_per_minute' => 15,
                'requests_per_hour' => 300,
            ],
        ],

        'facebook' => [
            'enabled' => env('FACEBOOK_ENABLED', true),
            'rate_limit' => [
                'requests_per_minute' => 15,
                'requests_per_hour' => 300,
            ],
        ],

        'twitter' => [
            'enabled' => env('TWITTER_ENABLED', true),
            'rate_limit' => [
                'requests_per_minute' => 15,
                'requests_per_hour' => 300,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook settings for real-time integration updates.
    |
    */
    'webhooks' => [
        'enabled' => env('INTEGRATION_WEBHOOKS_ENABLED', true),
        'timeout' => 30,
        'retry_attempts' => 3,
        'retry_delay' => 5000, // milliseconds
        'signature_verification' => env('INTEGRATION_WEBHOOK_VERIFICATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching settings for integration data.
    |
    */
    'cache' => [
        'enabled' => env('INTEGRATION_CACHE_ENABLED', true),
        'default_ttl' => 3600, // 1 hour
        'store' => env('INTEGRATION_CACHE_STORE', 'default'),
        'prefix' => 'integration:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for integration API calls.
    |
    */
    'rate_limiting' => [
        'enabled' => env('INTEGRATION_RATE_LIMITING_ENABLED', true),
        'default_limits' => [
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000,
            'requests_per_day' => 10000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for integration activities.
    |
    */
    'logging' => [
        'enabled' => env('INTEGRATION_LOGGING_ENABLED', true),
        'channel' => env('INTEGRATION_LOG_CHANNEL', 'integrations'),
        'level' => env('INTEGRATION_LOG_LEVEL', 'info'),
        'log_requests' => env('INTEGRATION_LOG_REQUESTS', true),
        'log_responses' => env('INTEGRATION_LOG_RESPONSES', false),
        'log_errors' => env('INTEGRATION_LOG_ERRORS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security settings for integrations.
    |
    */
    'security' => [
        'encrypt_config' => env('INTEGRATION_ENCRYPT_CONFIG', true),
        'allowed_ips' => env('INTEGRATION_ALLOWED_IPS', ''),
        'webhook_verification' => env('INTEGRATION_WEBHOOK_VERIFICATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-sync Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic synchronization settings.
    |
    */
    'auto_sync' => [
        'enabled' => env('INTEGRATION_AUTO_SYNC_ENABLED', false),
        'interval' => env('INTEGRATION_AUTO_SYNC_INTERVAL', 3600), // seconds
        'batch_size' => env('INTEGRATION_AUTO_SYNC_BATCH_SIZE', 100),
        'max_retries' => env('INTEGRATION_AUTO_SYNC_MAX_RETRIES', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Categories
    |--------------------------------------------------------------------------
    |
    | Define the available integration categories and their display settings.
    |
    */
    'categories' => [
        'analytics' => [
            'name' => 'Analytics',
            'description' => 'Data tracking and analytics platforms',
            'icon' => '📊',
            'color' => 'purple',
        ],
        'marketing' => [
            'name' => 'Marketing',
            'description' => 'Email marketing and automation tools',
            'icon' => '📧',
            'color' => 'green',
        ],
        'social' => [
            'name' => 'Social Media',
            'description' => 'Social media management and posting',
            'icon' => '📱',
            'color' => 'pink',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Features
    |--------------------------------------------------------------------------
    |
    | Configure which integration features are enabled.
    |
    */
    'features' => [
        'webhooks' => env('INTEGRATION_FEATURE_WEBHOOKS', false),
        'auto_sync' => env('INTEGRATION_FEATURE_AUTO_SYNC', false),
        'analytics' => env('INTEGRATION_FEATURE_ANALYTICS', true),
        'health_monitoring' => env('INTEGRATION_FEATURE_HEALTH_MONITORING', true),
        'error_reporting' => env('INTEGRATION_FEATURE_ERROR_REPORTING', true),
        'rate_limiting' => env('INTEGRATION_FEATURE_RATE_LIMITING', false),
    ],
];
