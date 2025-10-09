<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeIntegration extends BaseIntegration
{
    protected string $name = 'stripe';
    protected string $description = 'Connect to Stripe for payment processing and subscription management';
    protected string $category = 'payment';
    protected array $requiredFields = [
        'secret_key' => [
            'type' => 'password',
            'label' => 'Secret Key',
            'description' => 'Your Stripe secret key (starts with sk_)',
            'placeholder' => 'sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'publishable_key' => [
            'type' => 'text',
            'label' => 'Publishable Key',
            'description' => 'Your Stripe publishable key (starts with pk_)',
            'placeholder' => 'pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'webhook_secret' => [
            'type' => 'password',
            'label' => 'Webhook Secret',
            'description' => 'Your Stripe webhook endpoint secret',
            'placeholder' => 'whsec_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => false
        ]
    ];

    protected string $baseUrl = 'https://api.stripe.com/v1';

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
            $secretKey = $config['secret_key'] ?? $this->config['secret_key'];

            $response = Http::withBasicAuth($secretKey, '')
                ->get("{$this->baseUrl}/account");

            if ($response->successful()) {
                $accountInfo = $response->json();
                $this->log('info', 'Connection test successful', ['account' => $accountInfo['display_name']]);
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
            throw new \Exception('Failed to connect to Stripe');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        // Get account information
        $accountInfo = $this->getAccountInfo();
        $balance = $this->getBalance();

        $this->log('info', 'Successfully connected to Stripe', ['account' => $accountInfo['display_name']]);

        return [
            'account' => $accountInfo,
            'balance' => $balance,
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

            $this->log('info', 'Disconnected from Stripe');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Stripe', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_charges',
            'sync_customers',
            'sync_subscriptions',
            'sync_products',
            'create_customer',
            'create_charge',
            'create_subscription',
            'get_analytics'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_charges':
                return $this->syncCharges($data);
            case 'sync_customers':
                return $this->syncCustomers($data);
            case 'sync_subscriptions':
                return $this->syncSubscriptions($data);
            case 'create_customer':
                return $this->createCustomer($data);
            case 'create_charge':
                return $this->createCharge($data);
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
            // Sync charges
            if ($options['sync_charges'] ?? true) {
                $results['charges'] = $this->syncCharges($options);
            }

            // Sync customers
            if ($options['sync_customers'] ?? true) {
                $results['customers'] = $this->syncCustomers($options);
            }

            // Sync subscriptions
            if ($options['sync_subscriptions'] ?? true) {
                $results['subscriptions'] = $this->syncSubscriptions($options);
            }

            // Sync products
            if ($options['sync_products'] ?? true) {
                $results['products'] = $this->syncProducts($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'charges' => $results['charges']['count'] ?? 0,
                'customers' => $results['customers']['count'] ?? 0,
                'subscriptions' => $results['subscriptions']['count'] ?? 0,
                'products' => $results['products']['count'] ?? 0
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
        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/account");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get account information');
    }

    protected function getBalance(): array
    {
        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/balance");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get balance information');
    }

    protected function syncCharges(array $options = []): array
    {
        $params = [
            'limit' => $options['limit'] ?? 100
        ];

        if (isset($options['created'])) {
            $params['created'] = $options['created'];
        }

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/charges", $params);

        if ($response->successful()) {
            $charges = $response->json()['data'];

            $this->cache('charges', $charges, 1800);

            return [
                'count' => count($charges),
                'charges' => $charges
            ];
        }

        throw new \Exception('Failed to sync charges: ' . $response->body());
    }

    protected function syncCustomers(array $options = []): array
    {
        $params = [
            'limit' => $options['limit'] ?? 100
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/customers", $params);

        if ($response->successful()) {
            $customers = $response->json()['data'];

            $this->cache('customers', $customers, 3600);

            return [
                'count' => count($customers),
                'customers' => $customers
            ];
        }

        throw new \Exception('Failed to sync customers: ' . $response->body());
    }

    protected function syncSubscriptions(array $options = []): array
    {
        $params = [
            'limit' => $options['limit'] ?? 100,
            'status' => $options['status'] ?? 'all'
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/subscriptions", $params);

        if ($response->successful()) {
            $subscriptions = $response->json()['data'];

            $this->cache('subscriptions', $subscriptions, 1800);

            return [
                'count' => count($subscriptions),
                'subscriptions' => $subscriptions
            ];
        }

        throw new \Exception('Failed to sync subscriptions: ' . $response->body());
    }

    protected function syncProducts(array $options = []): array
    {
        $params = [
            'limit' => $options['limit'] ?? 100,
            'active' => $options['active'] ?? true
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/products", $params);

        if ($response->successful()) {
            $products = $response->json()['data'];

            $this->cache('products', $products, 3600);

            return [
                'count' => count($products),
                'products' => $products
            ];
        }

        throw new \Exception('Failed to sync products: ' . $response->body());
    }

    protected function createCustomer(array $data): array
    {
        $customerData = [
            'email' => $data['email'],
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'metadata' => $data['metadata'] ?? []
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->asForm()
            ->post("{$this->baseUrl}/customers", $customerData);

        if ($response->successful()) {
            $customer = $response->json();
            $this->log('info', 'Customer created successfully', ['customer_id' => $customer['id']]);
            return $customer;
        }

        throw new \Exception('Failed to create customer: ' . $response->body());
    }

    protected function createCharge(array $data): array
    {
        $chargeData = [
            'amount' => $data['amount'], // Amount in cents
            'currency' => $data['currency'] ?? 'usd',
            'customer' => $data['customer_id'] ?? '',
            'description' => $data['description'] ?? '',
            'source' => $data['source'] ?? '' // Token or card ID
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->asForm()
            ->post("{$this->baseUrl}/charges", $chargeData);

        if ($response->successful()) {
            $charge = $response->json();
            $this->log('info', 'Charge created successfully', ['charge_id' => $charge['id']]);
            return $charge;
        }

        throw new \Exception('Failed to create charge: ' . $response->body());
    }

    public function getAnalytics(): array
    {
        $analytics = [
            'charges_count' => $this->getCached('charges_count', 0),
            'customers_count' => $this->getCached('customers_count', 0),
            'subscriptions_count' => $this->getCached('subscriptions_count', 0),
            'products_count' => $this->getCached('products_count', 0),
            'last_sync' => $this->getCached('last_sync')
        ];

        return $analytics;
    }

    protected function setupWebhooks(): string
    {
        $webhookUrl = url('/webhooks/stripe');

        // Create webhook endpoint
        $webhookData = [
            'url' => $webhookUrl,
            'enabled_events' => [
                'charge.succeeded',
                'charge.failed',
                'customer.created',
                'customer.updated',
                'subscription.created',
                'subscription.updated',
                'subscription.deleted',
                'invoice.payment_succeeded',
                'invoice.payment_failed'
            ]
        ];

        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->asForm()
            ->post("{$this->baseUrl}/webhook_endpoints", $webhookData);

        if ($response->successful()) {
            $webhook = $response->json();
            $this->log('info', 'Webhook created successfully', ['webhook_id' => $webhook['id']]);
            return $webhookUrl;
        }

        $this->log('warning', 'Failed to create webhook', ['response' => $response->body()]);
        return '';
    }

    protected function removeWebhooks(): void
    {
        // Get existing webhooks
        $response = Http::withBasicAuth($this->config['secret_key'], '')
            ->get("{$this->baseUrl}/webhook_endpoints");

        if ($response->successful()) {
            $webhooks = $response->json()['data'];
            $webhookUrl = url('/webhooks/stripe');

            foreach ($webhooks as $webhook) {
                if (str_contains($webhook['url'], $webhookUrl)) {
                    Http::withBasicAuth($this->config['secret_key'], '')
                        ->delete("{$this->baseUrl}/webhook_endpoints/{$webhook['id']}");
                }
            }
        }
    }

    /**
     * Get Stripe documentation URL
     */
    public function getDocumentationUrl(): ?string
    {
        return 'https://stripe.com/docs/api';
    }

    /**
     * Get Stripe support URL
     */
    public function getSupportUrl(): ?string
    {
        return 'https://support.stripe.com';
    }
}
