<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HubSpotIntegration extends BaseIntegration
{
    protected string $name = 'hubspot';
    protected string $description = 'Connect your HubSpot account to sync contacts, companies, and deals';
    protected string $category = 'crm';
    protected array $requiredFields = [
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your HubSpot access token',
            'placeholder' => 'pat-na1-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'required' => true
        ]
    ];

    protected string $baseUrl = 'https://api.hubapi.com';

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
            ])->get("{$this->baseUrl}/crm/v3/objects/contacts", [
                'limit' => 1
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
            throw new \Exception('Failed to connect to HubSpot');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to HubSpot');

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

            $this->log('info', 'Disconnected from HubSpot');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from HubSpot', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_contacts',
            'sync_companies',
            'sync_deals',
            'create_contact',
            'update_contact'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_contacts':
                return $this->syncContacts($data);
            case 'sync_companies':
                return $this->syncCompanies($data);
            case 'sync_deals':
                return $this->syncDeals($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_contacts'] ?? true) {
                $results['contacts'] = $this->syncContacts($options);
            }

            if ($options['sync_companies'] ?? true) {
                $results['companies'] = $this->syncCompanies($options);
            }

            if ($options['sync_deals'] ?? true) {
                $results['deals'] = $this->syncDeals($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'contacts' => $results['contacts']['count'] ?? 0,
                'companies' => $results['companies']['count'] ?? 0,
                'deals' => $results['deals']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function syncContacts(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/crm/v3/objects/contacts", [
            'limit' => $options['limit'] ?? 100
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $contacts = $data['results'] ?? [];
            $this->cache('contacts', $contacts, 3600);

            return [
                'count' => count($contacts),
                'contacts' => $contacts
            ];
        }

        throw new \Exception('Failed to sync contacts: ' . $response->body());
    }

    protected function syncCompanies(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/crm/v3/objects/companies", [
            'limit' => $options['limit'] ?? 100
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $companies = $data['results'] ?? [];
            $this->cache('companies', $companies, 3600);

            return [
                'count' => count($companies),
                'companies' => $companies
            ];
        }

        throw new \Exception('Failed to sync companies: ' . $response->body());
    }

    protected function syncDeals(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/crm/v3/objects/deals", [
            'limit' => $options['limit'] ?? 100
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $deals = $data['results'] ?? [];
            $this->cache('deals', $deals, 3600);

            return [
                'count' => count($deals),
                'deals' => $deals
            ];
        }

        throw new \Exception('Failed to sync deals: ' . $response->body());
    }
}
