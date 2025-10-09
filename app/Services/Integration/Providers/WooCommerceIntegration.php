<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WooCommerceIntegration extends BaseIntegration
{
    protected string $name = 'woocommerce';
    protected string $description = 'Connect your WooCommerce store to sync products, orders, and customers';
    protected string $category = 'ecommerce';
    protected string $version = '1.0.0';
    protected array $requiredFields = [
        'store_url' => [
            'type' => 'url',
            'label' => 'Store URL',
            'description' => 'Your WooCommerce store URL (e.g., https://yourstore.com)',
            'placeholder' => 'https://yourstore.com',
            'required' => true
        ],
        'consumer_key' => [
            'type' => 'text',
            'label' => 'Consumer Key',
            'description' => 'Your WooCommerce consumer key',
            'placeholder' => 'ck_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'consumer_secret' => [
            'type' => 'password',
            'label' => 'Consumer Secret',
            'description' => 'Your WooCommerce consumer secret',
            'placeholder' => 'cs_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
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
        if (isset($this->config['store_url']) && !empty($this->config['store_url'])) {
            $this->baseUrl = rtrim($this->config['store_url'], '/') . '/wp-json/wc/v3';
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
            $storeUrl = $config['store_url'] ?? $this->config['store_url'];
            $consumerKey = $config['consumer_key'] ?? $this->config['consumer_key'];
            $consumerSecret = $config['consumer_secret'] ?? $this->config['consumer_secret'];

            $response = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get("{$storeUrl}/wp-json/wc/v3/system_status");

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
            throw new \Exception('Failed to connect to WooCommerce store');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        $this->log('info', 'Successfully connected to WooCommerce');

        return [
            'store_url' => $config['store_url'],
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

            $this->log('info', 'Disconnected from WooCommerce');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from WooCommerce', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_products',
            'sync_orders',
            'sync_customers',
            'create_product',
            'update_product'
        ];
    }

    public function executeOperation(string $operation, array $data = []): array
    {
        switch ($operation) {
            case 'sync_products':
                return $this->syncProducts($data);
            case 'sync_orders':
                return $this->syncOrders($data);
            case 'sync_customers':
                return $this->syncCustomers($data);
            default:
                throw new \Exception("Operation '{$operation}' not supported");
        }
    }

    public function sync(array $options = []): array
    {
        $results = [];
        $startTime = microtime(true);

        try {
            if ($options['sync_products'] ?? true) {
                $results['products'] = $this->syncProducts($options);
            }

            if ($options['sync_orders'] ?? true) {
                $results['orders'] = $this->syncOrders($options);
            }

            if ($options['sync_customers'] ?? true) {
                $results['customers'] = $this->syncCustomers($options);
            }

            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(true, $responseTime);

            $this->log('info', 'Sync completed successfully', [
                'products' => $results['products']['count'] ?? 0,
                'orders' => $results['orders']['count'] ?? 0,
                'customers' => $results['customers']['count'] ?? 0
            ]);

            return $results;
        } catch (\Exception $e) {
            $responseTime = microtime(true) - $startTime;
            $this->updateHealthMetrics(false, $responseTime);

            $this->log('error', 'Sync failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function syncProducts(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['consumer_key'], $this->config['consumer_secret'])
            ->get("{$this->baseUrl}/products", [
                'per_page' => $options['limit'] ?? 100
            ]);

        if ($response->successful()) {
            $products = $response->json();
            $this->cache('products', $products, 3600);

            return [
                'count' => count($products),
                'products' => $products
            ];
        }

        throw new \Exception('Failed to sync products: ' . $response->body());
    }

    protected function syncOrders(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['consumer_key'], $this->config['consumer_secret'])
            ->get("{$this->baseUrl}/orders", [
                'per_page' => $options['limit'] ?? 100,
                'status' => $options['status'] ?? 'any'
            ]);

        if ($response->successful()) {
            $orders = $response->json();
            $this->cache('orders', $orders, 1800);

            return [
                'count' => count($orders),
                'orders' => $orders
            ];
        }

        throw new \Exception('Failed to sync orders: ' . $response->body());
    }

    protected function syncCustomers(array $options = []): array
    {
        $response = Http::withBasicAuth($this->config['consumer_key'], $this->config['consumer_secret'])
            ->get("{$this->baseUrl}/customers", [
                'per_page' => $options['limit'] ?? 100
            ]);

        if ($response->successful()) {
            $customers = $response->json();
            $this->cache('customers', $customers, 3600);

            return [
                'count' => count($customers),
                'customers' => $customers
            ];
        }

        throw new \Exception('Failed to sync customers: ' . $response->body());
    }

    /**
     * Get WooCommerce-specific features
     */
    public function getFeatures(): array
    {
        return [
            'Product synchronization',
            'Order management',
            'Customer data sync',
            'Inventory tracking',
            'Real-time updates',
            'Error handling',
            'Health monitoring'
        ];
    }

    /**
     * Get WooCommerce version
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Get WooCommerce documentation URL
     */
    public function getDocumentationUrl(): ?string
    {
        return 'https://woocommerce.com/document/woocommerce-rest-api/';
    }

    /**
     * Get WooCommerce support URL
     */
    public function getSupportUrl(): ?string
    {
        return 'https://woocommerce.com/support/';
    }
}
