<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LinkedInIntegration extends BaseIntegration
{
    protected string $name = 'linkedin';
    protected string $description = 'Connect your LinkedIn account to sync posts and analytics';
    protected string $category = 'social';
    protected array $requiredFields = [
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your LinkedIn access token',
            'placeholder' => 'AQVxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'client_id' => [
            'type' => 'text',
            'label' => 'Client ID',
            'description' => 'Your LinkedIn client ID',
            'placeholder' => '86xxxxxxxxxxxxxxxx',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://api.linkedin.com/v2';

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

    public function testConnection(array $config): bool
    {
        try {
            $accessToken = $config['access_token'] ?? $this->config['access_token'];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->get("{$this->baseUrl}/me");

            if ($response->successful()) {
                $this->log('info', 'Connection test successful');
                return true;
            }

            $this->log('error', 'Connection test failed', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            $this->log('error', 'Connection test exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function connect(array $config): array
    {
        $errors = $this->validateConfig($config);
        if (!empty($errors)) {
            throw new \Exception('Configuration validation failed: ' . implode(', ', $errors));
        }

        if (!$this->testConnection($config)) {
            throw new \Exception('Failed to connect to LinkedIn');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to LinkedIn');

        return [
            'sync_status' => 'ready'
        ];
    }

    public function disconnect(): bool
    {
        try {
            $this->connected = false;
            $this->config = [];
            $this->saveConfiguration();
            $this->clearCache();

            $this->log('info', 'Disconnected from LinkedIn');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from LinkedIn', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_posts',
            'create_post',
            'get_analytics',
            'get_profile'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_posts':
                return $this->syncPosts($data);
            case 'create_post':
                return $this->createPost($data);
            case 'get_analytics':
                return $this->getAnalytics($data);
            case 'get_profile':
                return $this->getProfile($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_posts'] ?? true) {
                $results['posts'] = $this->syncPosts($options);
            }

            if ($options['sync_profile'] ?? true) {
                $results['profile'] = $this->getProfile($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'posts' => $results['posts']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function syncPosts(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/ugcPosts", [
            'q' => 'authors',
            'authors' => 'urn:li:person:' . $this->config['user_id'] ?? ''
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $posts = $data['elements'] ?? [];
            $this->cache('posts', $posts, 3600);

            return [
                'count' => count($posts),
                'posts' => $posts
            ];
        }

        throw new \Exception('Failed to sync posts: ' . $response->body());
    }

    protected function createPost(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}",
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/ugcPosts", [
            'author' => 'urn:li:person:' . $this->config['user_id'] ?? '',
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $data['text'] ?? ''
                    ],
                    'shareMediaCategory' => 'NONE'
                ]
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
            ]
        ]);

        if ($response->successful()) {
            $post = $response->json();
            $this->log('info', 'Post created successfully', ['post_id' => $post['id'] ?? 'unknown']);
            return $post;
        }

        throw new \Exception('Failed to create post: ' . $response->body());
    }

    public function getAnalytics(): array
    {
        // LinkedIn analytics require specific permissions and endpoints
        return [
            'posts_count' => $this->getCached('posts_count', 0),
            'last_sync' => $this->getCached('last_sync')
        ];
    }

    protected function getProfile(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/me");

        if ($response->successful()) {
            $profile = $response->json();
            $this->cache('profile', $profile, 3600);

            return [
                'profile' => $profile
            ];
        }

        throw new \Exception('Failed to get profile: ' . $response->body());
    }
}
