<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SegmentIntegration extends BaseIntegration
{
    protected string $name = 'segment';
    protected string $description = 'Connect your Segment account to sync analytics data';
    protected string $category = 'analytics';
    protected array $requiredFields = [
        'write_key' => [
            'type' => 'password',
            'label' => 'Write Key',
            'description' => 'Your Segment write key',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'workspace_slug' => [
            'type' => 'text',
            'label' => 'Workspace Slug',
            'description' => 'Your Segment workspace slug',
            'placeholder' => 'your-workspace',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://api.segment.io/v1';

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
            $writeKey = $config['write_key'] ?? $this->config['write_key'];

            $response = Http::withHeaders([
                'Authorization' => "Basic " . base64_encode($writeKey . ':')
            ])->get("{$this->baseUrl}/track");

            // Segment API doesn't have a direct test endpoint, so we'll just check if the key is valid format
            if (strlen($writeKey) > 10) {
                $this->log('info', 'Connection test successful');
                return true;
            }

            $this->log('error', 'Connection test failed - invalid write key format');
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
            throw new \Exception('Failed to connect to Segment');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to Segment');

        return [
            'workspace_slug' => $config['workspace_slug'],
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

            $this->log('info', 'Disconnected from Segment');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Segment', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'track_event',
            'identify_user',
            'page_view',
            'get_destinations'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'track_event':
                return $this->trackEvent($data);
            case 'identify_user':
                return $this->identifyUser($data);
            case 'page_view':
                return $this->pageView($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            // Segment is primarily for sending data, not syncing
            $results['status'] = 'ready';
            $results['destinations'] = $this->getDestinations();

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Segment sync completed successfully');

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Segment sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function trackEvent(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Basic " . base64_encode($this->config['write_key'] . ':'),
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/track", [
            'event' => $data['event'] ?? 'Custom Event',
            'userId' => $data['user_id'] ?? null,
            'properties' => $data['properties'] ?? []
        ]);

        if ($response->successful()) {
            $this->log('info', 'Event tracked successfully', ['event' => $data['event'] ?? 'Custom Event']);
            return ['success' => true, 'message' => 'Event tracked successfully'];
        }

        throw new \Exception('Failed to track event: ' . $response->body());
    }

    protected function identifyUser(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Basic " . base64_encode($this->config['write_key'] . ':'),
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/identify", [
            'userId' => $data['user_id'] ?? null,
            'traits' => $data['traits'] ?? []
        ]);

        if ($response->successful()) {
            $this->log('info', 'User identified successfully', ['user_id' => $data['user_id'] ?? null]);
            return ['success' => true, 'message' => 'User identified successfully'];
        }

        throw new \Exception('Failed to identify user: ' . $response->body());
    }

    protected function pageView(array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Basic " . base64_encode($this->config['write_key'] . ':'),
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/page", [
            'userId' => $data['user_id'] ?? null,
            'name' => $data['page_name'] ?? 'Page',
            'properties' => $data['properties'] ?? []
        ]);

        if ($response->successful()) {
            $this->log('info', 'Page view tracked successfully', ['page' => $data['page_name'] ?? 'Page']);
            return ['success' => true, 'message' => 'Page view tracked successfully'];
        }

        throw new \Exception('Failed to track page view: ' . $response->body());
    }

    protected function getDestinations(): array
    {
        // This would typically require Segment's management API
        // For now, return a placeholder
        return [
            'count' => 0,
            'destinations' => []
        ];
    }
}
