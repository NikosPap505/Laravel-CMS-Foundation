<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SalesforceIntegration extends BaseIntegration
{
    protected string $name = 'salesforce';
    protected string $description = 'Connect your Salesforce account to sync leads, contacts, and opportunities';
    protected string $category = 'crm';
    protected array $requiredFields = [
        'instance_url' => [
            'type' => 'url',
            'label' => 'Instance URL',
            'description' => 'Your Salesforce instance URL (e.g., https://yourcompany.salesforce.com)',
            'placeholder' => 'https://yourcompany.salesforce.com',
            'required' => true
        ],
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your Salesforce access token',
            'placeholder' => '00Dxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ]
    ];

    protected string $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->initializeFromConfig();
    }

    /**
     * Initialize integration properties from configuration
     */
    protected function initializeFromConfig(): void
    {
        if (isset($this->config['instance_url']) && !empty($this->config['instance_url'])) {
            $this->baseUrl = rtrim($this->config['instance_url'], '/') . '/services/data/v58.0';
        } else {
            $this->baseUrl = '';
        }
    }

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
            $instanceUrl = $config['instance_url'] ?? $this->config['instance_url'];
            $accessToken = $config['access_token'] ?? $this->config['access_token'];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->get("{$instanceUrl}/services/data/v58.0/sobjects/Account/describe");

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
            throw new \Exception('Failed to connect to Salesforce');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to Salesforce');

        return [
            'instance_url' => $config['instance_url'],
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

            $this->log('info', 'Disconnected from Salesforce');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Salesforce', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_leads',
            'sync_contacts',
            'sync_opportunities',
            'sync_accounts',
            'create_lead',
            'update_contact'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_leads':
                return $this->syncLeads($data);
            case 'sync_contacts':
                return $this->syncContacts($data);
            case 'sync_opportunities':
                return $this->syncOpportunities($data);
            case 'sync_accounts':
                return $this->syncAccounts($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_leads'] ?? true) {
                $results['leads'] = $this->syncLeads($options);
            }

            if ($options['sync_contacts'] ?? true) {
                $results['contacts'] = $this->syncContacts($options);
            }

            if ($options['sync_opportunities'] ?? true) {
                $results['opportunities'] = $this->syncOpportunities($options);
            }

            if ($options['sync_accounts'] ?? true) {
                $results['accounts'] = $this->syncAccounts($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'leads' => $results['leads']['count'] ?? 0,
                'contacts' => $results['contacts']['count'] ?? 0,
                'opportunities' => $results['opportunities']['count'] ?? 0,
                'accounts' => $results['accounts']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function syncLeads(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/sobjects/Lead", [
            'limit' => $options['limit'] ?? 200
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $leads = $data['records'] ?? [];
            $this->cache('leads', $leads, 3600);

            return [
                'count' => count($leads),
                'leads' => $leads
            ];
        }

        throw new \Exception('Failed to sync leads: ' . $response->body());
    }

    protected function syncContacts(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/sobjects/Contact", [
            'limit' => $options['limit'] ?? 200
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $contacts = $data['records'] ?? [];
            $this->cache('contacts', $contacts, 3600);

            return [
                'count' => count($contacts),
                'contacts' => $contacts
            ];
        }

        throw new \Exception('Failed to sync contacts: ' . $response->body());
    }

    protected function syncOpportunities(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/sobjects/Opportunity", [
            'limit' => $options['limit'] ?? 200
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $opportunities = $data['records'] ?? [];
            $this->cache('opportunities', $opportunities, 3600);

            return [
                'count' => count($opportunities),
                'opportunities' => $opportunities
            ];
        }

        throw new \Exception('Failed to sync opportunities: ' . $response->body());
    }

    protected function syncAccounts(array $options = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->config['access_token']}"
        ])->get("{$this->baseUrl}/sobjects/Account", [
            'limit' => $options['limit'] ?? 200
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $accounts = $data['records'] ?? [];
            $this->cache('accounts', $accounts, 3600);

            return [
                'count' => count($accounts),
                'accounts' => $accounts
            ];
        }

        throw new \Exception('Failed to sync accounts: ' . $response->body());
    }
}
