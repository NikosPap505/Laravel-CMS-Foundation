<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConvertKitIntegration extends BaseIntegration
{
    protected string $name = 'convertkit';
    protected string $description = 'Connect your ConvertKit account to sync subscribers and forms';
    protected string $category = 'marketing';
    protected array $requiredFields = [
        'api_key' => [
            'type' => 'password',
            'label' => 'API Key',
            'description' => 'Your ConvertKit API key',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'api_secret' => [
            'type' => 'password',
            'label' => 'API Secret',
            'description' => 'Your ConvertKit API secret',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://api.convertkit.com/v3';

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
            $apiKey = $config['api_key'] ?? $this->config['api_key'];

            $response = Http::get("{$this->baseUrl}/account", [
                'api_key' => $apiKey
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
            throw new \Exception('Failed to connect to ConvertKit');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to ConvertKit');

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

            $this->log('info', 'Disconnected from ConvertKit');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from ConvertKit', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_subscribers',
            'sync_forms',
            'sync_tags',
            'add_subscriber',
            'update_subscriber'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_subscribers':
                return $this->syncSubscribers($data);
            case 'sync_forms':
                return $this->syncForms($data);
            case 'sync_tags':
                return $this->syncTags($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_subscribers'] ?? true) {
                $results['subscribers'] = $this->syncSubscribers($options);
            }

            if ($options['sync_forms'] ?? true) {
                $results['forms'] = $this->syncForms($options);
            }

            if ($options['sync_tags'] ?? true) {
                $results['tags'] = $this->syncTags($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'subscribers' => $results['subscribers']['count'] ?? 0,
                'forms' => $results['forms']['count'] ?? 0,
                'tags' => $results['tags']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function syncSubscribers(array $options = []): array
    {
        $response = Http::get("{$this->baseUrl}/subscribers", [
            'api_key' => $this->config['api_key'],
            'page' => $options['page'] ?? 1
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $subscribers = $data['subscribers'] ?? [];
            $this->cache('subscribers', $subscribers, 3600);

            return [
                'count' => count($subscribers),
                'subscribers' => $subscribers
            ];
        }

        throw new \Exception('Failed to sync subscribers: ' . $response->body());
    }

    protected function syncForms(array $options = []): array
    {
        $response = Http::get("{$this->baseUrl}/forms", [
            'api_key' => $this->config['api_key']
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $forms = $data['forms'] ?? [];
            $this->cache('forms', $forms, 3600);

            return [
                'count' => count($forms),
                'forms' => $forms
            ];
        }

        throw new \Exception('Failed to sync forms: ' . $response->body());
    }

    protected function syncTags(array $options = []): array
    {
        $response = Http::get("{$this->baseUrl}/tags", [
            'api_key' => $this->config['api_key']
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $tags = $data['tags'] ?? [];
            $this->cache('tags', $tags, 3600);

            return [
                'count' => count($tags),
                'tags' => $tags
            ];
        }

        throw new \Exception('Failed to sync tags: ' . $response->body());
    }
}
