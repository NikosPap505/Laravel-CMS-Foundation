<?php

namespace App\Services\Integration;

use App\Models\Integration;
use App\Services\Integration\Contracts\IntegrationInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class IntegrationManager
{
    protected array $integrations = [];
    protected array $categories = [
        'analytics' => 'Analytics',
        'marketing' => 'Marketing',
        'social' => 'Social Media'
    ];

    public function __construct()
    {
        $this->registerIntegrations();
    }

    /**
     * Register all available integrations
     */
    protected function registerIntegrations(): void
    {
        $integrationClasses = [
            // Analytics
            'google_analytics_4' => \App\Services\Integration\Providers\GoogleAnalytics4Integration::class,
            'google_tag_manager' => \App\Services\Integration\Providers\GoogleTagManagerIntegration::class,

            // Marketing
            'mailchimp' => \App\Services\Integration\Providers\MailchimpIntegration::class,

            // Social Media
            'instagram' => \App\Services\Integration\Providers\InstagramIntegration::class,
            'facebook' => \App\Services\Integration\Providers\FacebookIntegration::class,
            'twitter' => \App\Services\Integration\Providers\TwitterIntegration::class,
        ];

        foreach ($integrationClasses as $name => $class) {
            if (class_exists($class)) {
                $this->integrations[$name] = $class;
            }
        }
    }

    /**
     * Get all available integrations
     */
    public function getAvailableIntegrations(): array
    {
        return $this->integrations;
    }

    /**
     * Get integration by name
     */
    public function getIntegration(string $name): ?IntegrationInterface
    {
        if (!isset($this->integrations[$name])) {
            return null;
        }

        return app($this->integrations[$name]);
    }

    /**
     * Get integrations by category
     */
    public function getIntegrationsByCategory(string $category): Collection
    {
        $integrations = collect();

        foreach ($this->integrations as $name => $class) {
            $integration = app($class);
            if ($integration->getCategory() === $category) {
                $integrations->put($name, $integration);
            }
        }

        return $integrations;
    }

    /**
     * Get all categories
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Connect to an integration
     */
    public function connect(string $name, array $config): array
    {
        $integration = $this->getIntegration($name);

        if (!$integration) {
            throw new \Exception("Integration '{$name}' not found");
        }

        try {
            $result = $integration->connect($config);

            Log::info("Integration connected successfully", [
                'integration' => $name,
                'config_keys' => array_keys($config)
            ]);

            return [
                'success' => true,
                'message' => "Successfully connected to {$integration->getDisplayName()}",
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error("Integration connection failed", [
                'integration' => $name,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => "Failed to connect to {$integration->getDisplayName()}: {$e->getMessage()}"
            ];
        }
    }

    /**
     * Disconnect from an integration
     */
    public function disconnect(string $name): array
    {
        $integration = $this->getIntegration($name);

        if (!$integration) {
            throw new \Exception("Integration '{$name}' not found");
        }

        try {
            $result = $integration->disconnect();

            Log::info("Integration disconnected successfully", [
                'integration' => $name
            ]);

            return [
                'success' => true,
                'message' => "Successfully disconnected from {$integration->getDisplayName()}"
            ];
        } catch (\Exception $e) {
            Log::error("Integration disconnection failed", [
                'integration' => $name,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => "Failed to disconnect from {$integration->getDisplayName()}: {$e->getMessage()}"
            ];
        }
    }

    /**
     * Test integration connection
     */
    public function testConnection(string $name, array $config = []): array
    {
        $integration = $this->getIntegration($name);

        if (!$integration) {
            throw new \Exception("Integration '{$name}' not found");
        }

        try {
            $isConnected = $integration->testConnection($config);

            return [
                'success' => $isConnected,
                'message' => $isConnected
                    ? "Connection to {$integration->getDisplayName()} successful"
                    : "Connection to {$integration->getDisplayName()} failed"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Connection test failed: {$e->getMessage()}"
            ];
        }
    }

    /**
     * Sync data from integration
     */
    public function sync(string $name, array $options = []): array
    {
        $integration = $this->getIntegration($name);

        if (!$integration) {
            throw new \Exception("Integration '{$name}' not found");
        }

        try {
            $result = $integration->sync($options);

            Log::info("Integration sync completed", [
                'integration' => $name,
                'options' => $options,
                'result_count' => count($result)
            ]);

            return [
                'success' => true,
                'message' => "Sync completed successfully",
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error("Integration sync failed", [
                'integration' => $name,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => "Sync failed: {$e->getMessage()}"
            ];
        }
    }

    /**
     * Get integration dashboard data
     */
    public function getDashboardData(): array
    {
        $data = [
            'categories' => $this->categories,
            'integrations' => [],
            'stats' => [
                'total' => count($this->integrations),
                'connected' => 0,
                'healthy' => 0,
                'errors' => 0
            ]
        ];

        foreach ($this->integrations as $name => $class) {
            $integration = app($class);
            $dbIntegration = Integration::where('name', $name)->first();

            $integrationData = [
                'name' => $name,
                'display_name' => $integration->getDisplayName(),
                'description' => $integration->getDescription(),
                'category' => $integration->getCategory(),
                'icon' => $this->getIntegrationIcon($name),
                'status' => $dbIntegration ? $dbIntegration->status : 'disconnected',
                'is_connected' => $dbIntegration ? $dbIntegration->is_connected : false,
                'last_sync' => $dbIntegration ? $dbIntegration->last_sync : null,
                'health_status' => $dbIntegration ? $dbIntegration->health_status : 'disconnected',
                'required_fields' => $integration->getRequiredFields(),
                'available_operations' => $integration->getAvailableOperations()
            ];

            $data['integrations'][$name] = $integrationData;

            // Update stats
            if ($integrationData['is_connected']) {
                $data['stats']['connected']++;

                if ($integrationData['health_status'] === 'healthy') {
                    $data['stats']['healthy']++;
                } elseif ($integrationData['health_status'] === 'error') {
                    $data['stats']['errors']++;
                }
            }
        }

        return $data;
    }

    /**
     * Get integration icon
     */
    protected function getIntegrationIcon(string $name): string
    {
        $icons = [
            'google_analytics_4' => $this->getGoogleAnalytics4Icon(),
            'google_tag_manager' => $this->getGoogleTagManagerIcon(),
            'mailchimp' => $this->getMailchimpIcon(),
            'instagram' => $this->getInstagramIcon(),
            'facebook' => $this->getFacebookIcon(),
            'twitter' => $this->getTwitterIcon()
        ];

        return $icons[$name] ?? '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
    }

    private function getGoogleAnalytics4Icon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>';
    }

    private function getGoogleTagManagerIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#4285F4"/>
            <path d="M7 8h10v2H7V8zm0 3h10v2H7v-2zm0 3h7v2H7v-2z" fill="white"/>
            <circle cx="17" cy="16" r="2" fill="#34A853"/>
        </svg>';
    }

    private function getMailchimpIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="#FFE01B"/>
        </svg>';
    }

    private function getInstagramIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="instagram-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#833AB4"/>
                    <stop offset="50%" style="stop-color:#E1306C"/>
                    <stop offset="100%" style="stop-color:#F77737"/>
                </linearGradient>
            </defs>
            <rect width="24" height="24" rx="6" fill="url(#instagram-gradient)"/>
            <circle cx="12" cy="12" r="4" fill="white"/>
            <circle cx="12" cy="12" r="2" fill="url(#instagram-gradient)"/>
            <circle cx="18" cy="6" r="1.5" fill="white"/>
        </svg>';
    }

    private function getFacebookIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#1877F2"/>
            <path d="M16.5 6h-3v-2c0-1.1.9-2 2-2h1c.55 0 1 .45 1 1v3h3v3h-3v8h-3v-8h-3V6h3z" fill="white"/>
        </svg>';
    }

    private function getTwitterIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#1DA1F2"/>
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" fill="white"/>
        </svg>';
    }
}
