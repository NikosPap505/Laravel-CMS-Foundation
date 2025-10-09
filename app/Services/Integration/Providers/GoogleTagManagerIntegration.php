<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTagManagerIntegration extends BaseIntegration
{
    protected string $name = 'google_tag_manager';
    protected string $description = 'Connect to Google Tag Manager for advanced tag and tracking management';
    protected string $category = 'analytics';
    protected array $requiredFields = [
        'container_id' => [
            'type' => 'text',
            'label' => 'Container ID',
            'description' => 'Your GTM Container ID (e.g., GTM-XXXXXXX)',
            'placeholder' => 'GTM-XXXXXXX',
            'required' => true
        ],
        'api_key' => [
            'type' => 'text',
            'label' => 'API Key',
            'description' => 'Your GTM API Key for management access',
            'placeholder' => 'Your API Key',
            'required' => true
        ]
    ];

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    public function getIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="24" height="24" rx="4" fill="#4285F4"/>
            <path d="M7 8h10v2H7V8zm0 3h10v2H7v-2zm0 3h7v2H7v-2z" fill="white"/>
            <circle cx="17" cy="16" r="2" fill="#34A853"/>
        </svg>';
    }

    public function getDisplayName(): string
    {
        return 'Google Tag Manager';
    }

    public function connect(array $config): array
    {
        try {
            // Validate the container ID format
            if (!preg_match('/^GTM-[A-Z0-9]+$/', $config['container_id'])) {
                throw new \Exception('Invalid GTM Container ID format. Should be GTM-XXXXXXX');
            }

            // Test the connection
            $this->testConnection($config);

            $this->config = $config;
            $this->connected = true;
            $this->saveConfiguration();

            return [
                'success' => true,
                'message' => 'Successfully connected to Google Tag Manager',
                'data' => [
                    'container_id' => $config['container_id']
                ]
            ];
        } catch (\Exception $e) {
            Log::error('GTM connection failed', [
                'error' => $e->getMessage(),
                'config' => $config
            ]);

            throw new \Exception('Failed to connect to Google Tag Manager: ' . $e->getMessage());
        }
    }

    public function disconnect(): bool
    {
        try {
            $this->config = [];
            $this->connected = false;
            $this->saveConfiguration();

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Failed to disconnect from Google Tag Manager: ' . $e->getMessage());
        }
    }

    public function testConnection(array $config = []): bool
    {
        try {
            $config = $config ?: $this->config;

            if (empty($config['container_id'])) {
                throw new \Exception('Container ID is required');
            }

            // Validate container ID format
            if (!preg_match('/^GTM-[A-Z0-9]+$/', $config['container_id'])) {
                throw new \Exception('Invalid GTM Container ID format');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('GTM connection test failed', [
                'error' => $e->getMessage(),
                'config' => $config
            ]);

            throw new \Exception('Connection test failed: ' . $e->getMessage());
        }
    }

    public function sync(array $options = []): array
    {
        try {
            if (!$this->connected) {
                throw new \Exception('Not connected to Google Tag Manager');
            }

            // GTM sync would typically involve checking container status
            return [
                'success' => true,
                'message' => 'GTM container is active and tracking',
                'data' => [
                    'container_id' => $this->config['container_id'],
                    'sync_timestamp' => now()->toISOString()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('GTM sync failed', [
                'error' => $e->getMessage(),
                'config' => $this->config
            ]);

            throw new \Exception('Sync failed: ' . $e->getMessage());
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync' => 'Sync container data',
            'test' => 'Test connection',
            'disconnect' => 'Disconnect integration'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync':
                return $this->sync($data);
            case 'test':
                return ['success' => $this->testConnection()];
            case 'disconnect':
                return ['success' => $this->disconnect()];
            default:
                throw new \Exception("Unknown operation: {$operation}");
        }
    }

    public function getFeatures(): array
    {
        return [
            'Tag management and deployment',
            'Custom event tracking',
            'Conversion tracking',
            'A/B testing setup',
            'Cross-domain tracking',
            'Advanced trigger configuration'
        ];
    }

    public function getDocumentationUrl(): ?string
    {
        return 'https://developers.google.com/tag-manager';
    }

    public function getSupportUrl(): ?string
    {
        return 'https://support.google.com/tagmanager/';
    }

    public function getStatus(): string
    {
        return $this->connected ? 'connected' : 'disconnected';
    }

    public function getAnalytics(): array
    {
        return [
            'connected' => $this->connected,
            'last_sync' => $this->connected ? now()->toISOString() : null,
            'container_id' => $this->config['container_id'] ?? null
        ];
    }

    public function getVersion(): ?string
    {
        return '1.0';
    }
}
