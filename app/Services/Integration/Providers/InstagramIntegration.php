<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramIntegration extends BaseIntegration
{
    protected string $name = 'instagram';
    protected string $description = 'Connect to Instagram for social media management and content publishing';
    protected string $category = 'social';
    protected array $requiredFields = [
        'access_token' => [
            'type' => 'text',
            'label' => 'Access Token',
            'description' => 'Your Instagram Basic Display API access token',
            'placeholder' => 'Your Access Token',
            'required' => true
        ],
        'app_id' => [
            'type' => 'text',
            'label' => 'App ID',
            'description' => 'Your Instagram App ID',
            'placeholder' => 'Your App ID',
            'required' => true
        ],
        'app_secret' => [
            'type' => 'text',
            'label' => 'App Secret',
            'description' => 'Your Instagram App Secret',
            'placeholder' => 'Your App Secret',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://graph.instagram.com';

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

    public function getDisplayName(): string
    {
        return 'Instagram';
    }

    public function connect(array $config): array
    {
        try {
            // Test the connection by making an API call
            $this->testConnection($config);

            $this->config = $config;
            $this->connected = true;
            $this->saveConfiguration();

            return [
                'success' => true,
                'message' => 'Successfully connected to Instagram',
                'data' => [
                    'app_id' => $config['app_id']
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Instagram connection failed', [
                'error' => $e->getMessage(),
                'config' => $config
            ]);

            throw new \Exception('Failed to connect to Instagram: ' . $e->getMessage());
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
            throw new \Exception('Failed to disconnect from Instagram: ' . $e->getMessage());
        }
    }

    public function testConnection(array $config = []): bool
    {
        try {
            $config = $config ?: $this->config;

            if (empty($config['access_token'])) {
                throw new \Exception('Access token is required');
            }

            // Test the connection by making a simple API call
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $config['access_token']
            ])->get($this->baseUrl . '/me', [
                'fields' => 'id,username'
            ]);

            if (!$response->successful()) {
                throw new \Exception('Invalid access token or API error');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Instagram connection test failed', [
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
                throw new \Exception('Not connected to Instagram');
            }

            // Get basic user info
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['access_token']
            ])->get($this->baseUrl . '/me', [
                'fields' => 'id,username,account_type,media_count'
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch Instagram data');
            }

            $userData = $response->json();

            return [
                'success' => true,
                'message' => 'Instagram data synced successfully',
                'data' => [
                    'user_id' => $userData['id'] ?? null,
                    'username' => $userData['username'] ?? null,
                    'account_type' => $userData['account_type'] ?? null,
                    'media_count' => $userData['media_count'] ?? 0,
                    'sync_timestamp' => now()->toISOString()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Instagram sync failed', [
                'error' => $e->getMessage(),
                'config' => $this->config
            ]);

            throw new \Exception('Sync failed: ' . $e->getMessage());
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync' => 'Sync Instagram data',
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
            'Profile management',
            'Media content access',
            'Story insights',
            'Post scheduling',
            'Engagement analytics',
            'Hashtag tracking'
        ];
    }

    public function getDocumentationUrl(): ?string
    {
        return 'https://developers.facebook.com/docs/instagram-basic-display-api';
    }

    public function getSupportUrl(): ?string
    {
        return 'https://help.instagram.com/';
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
            'app_id' => $this->config['app_id'] ?? null
        ];
    }

    public function getVersion(): ?string
    {
        return '1.0';
    }
}
