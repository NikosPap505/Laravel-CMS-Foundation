<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailchimpIntegration extends BaseIntegration
{
    protected string $name = 'mailchimp';
    protected string $description = 'Connect to Mailchimp for email marketing automation and subscriber management';
    protected string $category = 'marketing';
    protected array $requiredFields = [
        'api_key' => [
            'type' => 'password',
            'label' => 'API Key',
            'description' => 'Your Mailchimp API key (get from Account > Extras > API keys)',
            'placeholder' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-us1',
            'required' => true
        ],
        'server_prefix' => [
            'type' => 'text',
            'label' => 'Server Prefix',
            'description' => 'Your Mailchimp server prefix (e.g., us1, us2, eu1)',
            'placeholder' => 'us1',
            'required' => true
        ],
        'default_list_id' => [
            'type' => 'text',
            'label' => 'Default List ID',
            'description' => 'Default audience/list ID for new subscribers',
            'placeholder' => 'xxxxxxxxxx',
            'required' => false
        ]
    ];

    protected string $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $serverPrefix = $this->config['server_prefix'] ?? 'us1';
        $this->baseUrl = "https://{$serverPrefix}.api.mailchimp.com/3.0";
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

    public function getIcon(): string
    {
        return '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="#FFE01B"/>
        </svg>';
    }

    public function testConnection(array $config): bool
    {
        try {
            $apiKey = $config['api_key'] ?? $this->config['api_key'];
            $serverPrefix = $config['server_prefix'] ?? $this->config['server_prefix'] ?? 'us1';

            $response = Http::withBasicAuth('anystring', $apiKey)
                ->get("https://{$serverPrefix}.api.mailchimp.com/3.0/");

            if ($response->successful()) {
                $accountInfo = $response->json();
                $this->log('info', 'Connection test successful', ['account' => $accountInfo['account_name']]);
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
            throw new \Exception('Failed to connect to Mailchimp');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        // Get account information
        $accountInfo = $this->getAccountInfo();
        $lists = $this->getLists();

        $this->log('info', 'Successfully connected to Mailchimp', ['account' => $accountInfo['account_name']]);

        return [
            'account' => $accountInfo,
            'lists' => $lists,
            'webhook_url' => $this->setupWebhooks(),
            'sync_status' => 'ready'
        ];
    }

    public function disconnect(): bool
    {
        try {
            // Remove webhooks
            $this->removeWebhooks();

            $this->connected = false;
            $this->config = [];
            $this->saveConfiguration();
            $this->clearCache();

            $this->log('info', 'Disconnected from Mailchimp');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Mailchimp', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_lists',
            'sync_subscribers',
            'sync_campaigns',
            'add_subscriber',
            'update_subscriber',
            'create_campaign',
            'send_campaign',
            'get_analytics'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_lists':
                return $this->syncLists($data);
            case 'sync_subscribers':
                return $this->syncSubscribers($data);
            case 'add_subscriber':
                return $this->addSubscriber($data);
            case 'create_campaign':
                return $this->createCampaign($data);
            case 'get_analytics':
                return $this->getAnalytics();
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            // Sync lists
            if ($options['sync_lists'] ?? true) {
                $results['lists'] = $this->syncLists($options);
            }

            // Sync subscribers
            if ($options['sync_subscribers'] ?? true) {
                $results['subscribers'] = $this->syncSubscribers($options);
            }

            // Sync campaigns
            if ($options['sync_campaigns'] ?? true) {
                $results['campaigns'] = $this->syncCampaigns($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'lists' => $results['lists']['count'] ?? 0,
                'subscribers' => $results['subscribers']['count'] ?? 0,
                'campaigns' => $results['campaigns']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getAccountInfo(): array
    {
        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->get("{$this->baseUrl}/");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get account information');
    }

    protected function getLists(): array
    {
        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->get("{$this->baseUrl}/lists", [
                'count' => 100
            ]);

        if ($response->successful()) {
            return $response->json()['lists'];
        }

        throw new \Exception('Failed to get lists');
    }

    protected function syncLists(array $options = []): array
    {
        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->get("{$this->baseUrl}/lists", [
                'count' => $options['count'] ?? 100
            ]);

        if ($response->successful()) {
            $lists = $response->json()['lists'];

            $this->cache('lists', $lists, 3600);

            return [
                'count' => count($lists),
                'lists' => $lists
            ];
        }

        throw new \Exception('Failed to sync lists: ' . $response->body());
    }

    protected function syncSubscribers(array $options = []): array
    {
        $listId = $options['list_id'] ?? $this->config['default_list_id'];

        if (!$listId) {
            throw new \Exception('List ID is required for subscriber sync');
        }

        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->get("{$this->baseUrl}/lists/{$listId}/members", [
                'count' => $options['count'] ?? 1000,
                'status' => $options['status'] ?? 'subscribed'
            ]);

        if ($response->successful()) {
            $subscribers = $response->json()['members'];

            $this->cache("subscribers_{$listId}", $subscribers, 1800);

            return [
                'count' => count($subscribers),
                'subscribers' => $subscribers,
                'list_id' => $listId
            ];
        }

        throw new \Exception('Failed to sync subscribers: ' . $response->body());
    }

    protected function syncCampaigns(array $options = []): array
    {
        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->get("{$this->baseUrl}/campaigns", [
                'count' => $options['count'] ?? 100,
                'status' => $options['status'] ?? 'sent'
            ]);

        if ($response->successful()) {
            $campaigns = $response->json()['campaigns'];

            $this->cache('campaigns', $campaigns, 3600);

            return [
                'count' => count($campaigns),
                'campaigns' => $campaigns
            ];
        }

        throw new \Exception('Failed to sync campaigns: ' . $response->body());
    }

    protected function addSubscriber(array $data): array
    {
        $listId = $data['list_id'] ?? $this->config['default_list_id'];

        if (!$listId) {
            throw new \Exception('List ID is required');
        }

        $subscriberData = [
            'email_address' => $data['email'],
            'status' => $data['status'] ?? 'subscribed',
            'merge_fields' => $data['merge_fields'] ?? [],
            'tags' => $data['tags'] ?? []
        ];

        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->post("{$this->baseUrl}/lists/{$listId}/members", $subscriberData);

        if ($response->successful()) {
            $subscriber = $response->json();
            $this->log('info', 'Subscriber added successfully', ['email' => $data['email']]);
            return $subscriber;
        }

        throw new \Exception('Failed to add subscriber: ' . $response->body());
    }

    protected function createCampaign(array $data): array
    {
        $campaignData = [
            'type' => $data['type'] ?? 'regular',
            'recipients' => [
                'list_id' => $data['list_id'] ?? $this->config['default_list_id']
            ],
            'settings' => [
                'subject_line' => $data['subject'] ?? 'New Campaign',
                'from_name' => $data['from_name'] ?? 'Your Company',
                'reply_to' => $data['reply_to'] ?? 'noreply@yourcompany.com'
            ]
        ];

        $response = Http::withBasicAuth('anystring', $this->config['api_key'])
            ->post("{$this->baseUrl}/campaigns", $campaignData);

        if ($response->successful()) {
            $campaign = $response->json();
            $this->log('info', 'Campaign created successfully', ['campaign_id' => $campaign['id']]);
            return $campaign;
        }

        throw new \Exception('Failed to create campaign: ' . $response->body());
    }

    public function getAnalytics(): array
    {
        $analytics = [
            'lists_count' => $this->getCached('lists_count', 0),
            'subscribers_count' => $this->getCached('subscribers_count', 0),
            'campaigns_count' => $this->getCached('campaigns_count', 0),
            'last_sync' => $this->getCached('last_sync')
        ];

        return $analytics;
    }

    protected function setupWebhooks(): string
    {
        $webhookUrl = url('/webhooks/mailchimp');

        // Create webhook for list updates
        $listId = $this->config['default_list_id'];

        if ($listId) {
            $response = Http::withBasicAuth('anystring', $this->config['api_key'])
                ->post("{$this->baseUrl}/lists/{$listId}/webhooks", [
                    'url' => $webhookUrl,
                    'events' => [
                        'subscribe' => true,
                        'unsubscribe' => true,
                        'profile' => true,
                        'cleaned' => true,
                        'upemail' => true,
                        'campaign' => true
                    ]
                ]);

            if ($response->successful()) {
                $this->log('info', 'Webhook created successfully', ['url' => $webhookUrl]);
                return $webhookUrl;
            }
        }

        $this->log('warning', 'Failed to create webhook', ['response' => $response->body() ?? 'No response']);
        return '';
    }

    protected function removeWebhooks(): void
    {
        $listId = $this->config['default_list_id'];

        if ($listId) {
            // Get existing webhooks
            $response = Http::withBasicAuth('anystring', $this->config['api_key'])
                ->get("{$this->baseUrl}/lists/{$listId}/webhooks");

            if ($response->successful()) {
                $webhooks = $response->json()['webhooks'];
                $webhookUrl = url('/webhooks/mailchimp');

                foreach ($webhooks as $webhook) {
                    if (str_contains($webhook['url'], $webhookUrl)) {
                        Http::withBasicAuth('anystring', $this->config['api_key'])
                            ->delete("{$this->baseUrl}/lists/{$listId}/webhooks/{$webhook['id']}");
                    }
                }
            }
        }
    }

    /**
     * Get Mailchimp documentation URL
     */
    public function getDocumentationUrl(): ?string
    {
        return 'https://mailchimp.com/developer/marketing/api/';
    }

    /**
     * Get Mailchimp support URL
     */
    public function getSupportUrl(): ?string
    {
        return 'https://mailchimp.com/help/';
    }
}
