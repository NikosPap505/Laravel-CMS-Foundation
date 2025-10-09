<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookIntegration extends BaseIntegration
{
    protected string $name = 'facebook';
    protected string $description = 'Connect your Facebook account to sync posts and analytics';
    protected string $category = 'social';
    protected array $requiredFields = [
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your Facebook access token',
            'placeholder' => 'EAAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'page_id' => [
            'type' => 'text',
            'label' => 'Page ID',
            'description' => 'Your Facebook page ID',
            'placeholder' => '123456789012345',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://graph.facebook.com/v18.0';

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
            <rect width="24" height="24" rx="4" fill="#1877F2"/>
            <path d="M16.5 6h-3v-2c0-1.1.9-2 2-2h1c.55 0 1 .45 1 1v3h3v3h-3v8h-3v-8h-3V6h3z" fill="white"/>
        </svg>';
    }

    public function testConnection(array $config): bool
    {
        try {
            $accessToken = $config['access_token'] ?? $this->config['access_token'];
            $pageId = $config['page_id'] ?? $this->config['page_id'];

            $response = Http::get("{$this->baseUrl}/{$pageId}", [
                'access_token' => $accessToken,
                'fields' => 'id,name'
            ]);

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
            throw new \Exception('Failed to connect to Facebook');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to Facebook');

        return [
            'page_id' => $config['page_id'],
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

            $this->log('info', 'Disconnected from Facebook');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Facebook', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_posts',
            'create_post',
            'get_analytics',
            'get_page_info'
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
            case 'get_page_info':
                return $this->getPageInfo($data);
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

            if ($options['sync_page_info'] ?? true) {
                $results['page_info'] = $this->getPageInfo($options);
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
        $response = Http::get("{$this->baseUrl}/{$this->config['page_id']}/posts", [
            'access_token' => $this->config['access_token'],
            'limit' => $options['limit'] ?? 25,
            'fields' => 'id,message,created_time,likes.summary(true),comments.summary(true)'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $posts = $data['data'] ?? [];
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
        $response = Http::post("{$this->baseUrl}/{$this->config['page_id']}/feed", [
            'access_token' => $this->config['access_token'],
            'message' => $data['message'] ?? '',
            'link' => $data['link'] ?? null
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
        $response = Http::get("{$this->baseUrl}/{$this->config['page_id']}/insights", [
            'access_token' => $this->config['access_token'],
            'metric' => 'page_impressions,page_reach,page_engaged_users',
            'period' => 'day',
            'since' => now()->subDays(7)->timestamp,
            'until' => now()->timestamp
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $insights = $data['data'] ?? [];
            $this->cache('analytics', $insights, 3600);

            return [
                'insights' => $insights
            ];
        }

        throw new \Exception('Failed to get analytics: ' . $response->body());
    }

    protected function getPageInfo(array $options = []): array
    {
        $response = Http::get("{$this->baseUrl}/{$this->config['page_id']}", [
            'access_token' => $this->config['access_token'],
            'fields' => 'id,name,about,fan_count,website'
        ]);

        if ($response->successful()) {
            $pageInfo = $response->json();
            $this->cache('page_info', $pageInfo, 3600);

            return [
                'page_info' => $pageInfo
            ];
        }

        throw new \Exception('Failed to get page info: ' . $response->body());
    }
}
