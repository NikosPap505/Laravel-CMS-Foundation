<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MixpanelIntegration extends BaseIntegration
{
    protected string $name = 'mixpanel';
    protected string $description = 'Connect your Mixpanel account to sync analytics data';
    protected string $category = 'analytics';
    protected array $requiredFields = [
        'project_id' => [
            'type' => 'text',
            'label' => 'Project ID',
            'description' => 'Your Mixpanel project ID',
            'placeholder' => '1234567890',
            'required' => true
        ],
        'service_account_secret' => [
            'type' => 'password',
            'label' => 'Service Account Secret',
            'description' => 'Your Mixpanel service account secret',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://mixpanel.com/api/2.0';

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
            $projectId = $config['project_id'] ?? $this->config['project_id'];
            $secret = $config['service_account_secret'] ?? $this->config['service_account_secret'];

            $response = Http::withBasicAuth($projectId, $secret)
                ->get("{$this->baseUrl}/events/properties/top");

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
            throw new \Exception('Failed to connect to Mixpanel');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to Mixpanel');

        return [
            'project_id' => $config['project_id'],
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

            $this->log('info', 'Disconnected from Mixpanel');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Mixpanel', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'get_events',
            'get_funnels',
            'get_retention',
            'get_cohorts'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'get_events':
                return $this->getEvents($data);
            case 'get_funnels':
                return $this->getFunnels($data);
            case 'get_retention':
                return $this->getRetention($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_events'] ?? true) {
                $results['events'] = $this->getEvents($options);
            }

            if ($options['sync_funnels'] ?? true) {
                $results['funnels'] = $this->getFunnels($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'events' => $results['events']['count'] ?? 0,
                'funnels' => $results['funnels']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getEvents(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['project_id'], $this->config['service_account_secret'])
            ->get("{$this->baseUrl}/events/properties/top");

        if ($response->successful()) {
            $data = $response->json();
            $events = $data['data'] ?? [];
            $this->cache('events', $events, 3600);

            return [
                'count' => count($events),
                'events' => $events
            ];
        }

        throw new \Exception('Failed to get events: ' . $response->body());
    }

    protected function getFunnels(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['project_id'], $this->config['service_account_secret'])
            ->get("{$this->baseUrl}/funnels/list");

        if ($response->successful()) {
            $data = $response->json();
            $funnels = $data['data'] ?? [];
            $this->cache('funnels', $funnels, 3600);

            return [
                'count' => count($funnels),
                'funnels' => $funnels
            ];
        }

        throw new \Exception('Failed to get funnels: ' . $response->body());
    }

    protected function getRetention(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['project_id'], $this->config['service_account_secret'])
            ->get("{$this->baseUrl}/retention");

        if ($response->successful()) {
            $data = $response->json();
            $retention = $data['data'] ?? [];
            $this->cache('retention', $retention, 3600);

            return [
                'count' => count($retention),
                'retention' => $retention
            ];
        }

        throw new \Exception('Failed to get retention data: ' . $response->body());
    }
}
