<?php

namespace App\Services\Integration\Providers;

use App\Services\Integration\BaseIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyIntegration extends BaseIntegration
{
    protected string $name = 'shopify';
    protected string $description = 'Connect your Shopify store to sync products, orders, and customers';
    protected string $category = 'ecommerce';
    protected array $requiredFields = [
        'shop_domain' => [
            'type' => 'text',
            'label' => 'Shop Domain',
            'description' => 'Your Shopify store domain (e.g., mystore.myshopify.com)',
            'placeholder' => 'mystore.myshopify.com',
            'required' => true
        ],
        'access_token' => [
            'type' => 'password',
            'label' => 'Access Token',
            'description' => 'Your Shopify private app access token',
            'placeholder' => 'shpat_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'required' => true
        ],
        'api_version' => [
            'type' => 'text',
            'label' => 'API Version',
            'description' => 'Shopify API version (default: 2023-10)',
            'placeholder' => '2023-10',
            'required' => false
        ]
    ];

    protected string $baseUrl;
    protected string $apiVersion;

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
        $this->apiVersion = $this->config['api_version'] ?? '2023-10';

        // Only set baseUrl if shop_domain is available
        if (isset($this->config['shop_domain']) && !empty($this->config['shop_domain'])) {
            $this->baseUrl = "https://{$this->config['shop_domain']}/admin/api/{$this->apiVersion}";
        } else {
            // Set a default baseUrl to prevent errors when not configured
            $this->baseUrl = '';
        }
    }

    /**
     * Ensure baseUrl is initialized before making API calls
     */
    protected function ensureBaseUrl(): void
    {
        if (empty($this->baseUrl)) {
            if (!isset($this->config['shop_domain']) || empty($this->config['shop_domain'])) {
                throw new \Exception('Shop domain is required but not configured');
            }
            $this->baseUrl = "https://{$this->config['shop_domain']}/admin/api/{$this->apiVersion}";
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
            $shopDomain = $config['shop_domain'] ?? $this->config['shop_domain'];
            $accessToken = $config['access_token'] ?? $this->config['access_token'];
            $apiVersion = $config['api_version'] ?? $this->apiVersion;

            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json'
            ])->get("https://{$shopDomain}/admin/api/{$apiVersion}/shop.json");

            if ($response->successful()) {
                $shopData = $response->json();
                $this->log('info', 'Connection test successful', ['shop' => $shopData['shop']['name']]);
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
            throw new \Exception('Failed to connect to Shopify store');
        }

        $this->config = array_merge($this->config, $config);
        $this->connected = true;
        $this->saveConfiguration();

        // Get shop information
        $shopInfo = $this->getShopInfo();

        $this->log('info', 'Successfully connected to Shopify', ['shop' => $shopInfo['name']]);

        return [
            'shop' => $shopInfo,
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

            $this->log('info', 'Disconnected from Shopify');
            return true;
        } catch (\Exception $e) {
            $this->log('error', 'Failed to disconnect from Shopify', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAvailableOperations(): array
    {
        return [
            'sync_products',
            'sync_orders',
            'sync_customers',
            'sync_inventory',
            'create_product',
            'update_product',
            'create_order',
            'get_analytics'
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
            case 'create_product':
                return $this->createProduct($data);
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
            // Sync products
            if ($options['sync_products'] ?? true) {
                $results['products'] = $this->syncProducts($options);
            }

            // Sync orders
            if ($options['sync_orders'] ?? true) {
                $results['orders'] = $this->syncOrders($options);
            }

            // Sync customers
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

    protected function getShopInfo(): array
    {
        $this->ensureBaseUrl();

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token']
        ])->get("{$this->baseUrl}/shop.json");

        if ($response->successful()) {
            return $response->json()['shop'];
        }

        throw new \Exception('Failed to get shop information');
    }

    protected function syncProducts(array $options = []): array
    {
        $this->ensureBaseUrl();

        $limit = $options['limit'] ?? 250;
        $page = $options['page'] ?? 1;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token']
        ])->get("{$this->baseUrl}/products.json", [
            'limit' => $limit,
            'page' => $page
        ]);

        if ($response->successful()) {
            $products = $response->json()['products'];

            // Store products in cache for quick access
            $this->cache('products', $products, 3600);

            return [
                'count' => count($products),
                'products' => $products,
                'page' => $page,
                'limit' => $limit
            ];
        }

        throw new \Exception('Failed to sync products: ' . $response->body());
    }

    protected function syncOrders(array $options = []): array
    {
        $this->ensureBaseUrl();

        $limit = $options['limit'] ?? 250;
        $status = $options['status'] ?? 'any';

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token']
        ])->get("{$this->baseUrl}/orders.json", [
            'limit' => $limit,
            'status' => $status
        ]);

        if ($response->successful()) {
            $orders = $response->json()['orders'];

            $this->cache('orders', $orders, 1800); // Cache for 30 minutes

            return [
                'count' => count($orders),
                'orders' => $orders,
                'status' => $status
            ];
        }

        throw new \Exception('Failed to sync orders: ' . $response->body());
    }

    protected function syncCustomers(array $options = []): array
    {
        $this->ensureBaseUrl();

        $limit = $options['limit'] ?? 250;

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token']
        ])->get("{$this->baseUrl}/customers.json", [
            'limit' => $limit
        ]);

        if ($response->successful()) {
            $customers = $response->json()['customers'];

            $this->cache('customers', $customers, 3600);

            return [
                'count' => count($customers),
                'customers' => $customers
            ];
        }

        throw new \Exception('Failed to sync customers: ' . $response->body());
    }

    protected function createProduct(array $data): array
    {
        $this->ensureBaseUrl();

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token'],
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/products.json", [
            'product' => $data
        ]);

        if ($response->successful()) {
            $product = $response->json()['product'];
            $this->log('info', 'Product created successfully', ['product_id' => $product['id']]);
            return $product;
        }

        throw new \Exception('Failed to create product: ' . $response->body());
    }

    public function getAnalytics(): array
    {
        // Get basic analytics data
        $analytics = [
            'products_count' => $this->getCached('products_count', 0),
            'orders_count' => $this->getCached('orders_count', 0),
            'customers_count' => $this->getCached('customers_count', 0),
            'last_sync' => $this->getCached('last_sync')
        ];

        return $analytics;
    }

    protected function setupWebhooks(): string
    {
        $this->ensureBaseUrl();

        $webhookUrl = url('/webhooks/shopify');

        // Create webhook for order updates
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token'],
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/webhooks.json", [
            'webhook' => [
                'topic' => 'orders/updated',
                'address' => $webhookUrl,
                'format' => 'json'
            ]
        ]);

        if ($response->successful()) {
            $this->log('info', 'Webhook created successfully', ['url' => $webhookUrl]);
            return $webhookUrl;
        }

        $this->log('warning', 'Failed to create webhook', ['response' => $response->body()]);
        return '';
    }

    protected function removeWebhooks(): void
    {
        $this->ensureBaseUrl();

        // Get existing webhooks
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->config['access_token']
        ])->get("{$this->baseUrl}/webhooks.json");

        if ($response->successful()) {
            $webhooks = $response->json()['webhooks'];
            $webhookUrl = url('/webhooks/shopify');

            foreach ($webhooks as $webhook) {
                if (str_contains($webhook['address'], $webhookUrl)) {
                    Http::withHeaders([
                        'X-Shopify-Access-Token' => $this->config['access_token']
                    ])->delete("{$this->baseUrl}/webhooks/{$webhook['id']}.json");
                }
            }
        }
    }

    /**
     * Get Shopify documentation URL
     */
    public function getDocumentationUrl(): ?string
    {
        return 'https://shopify.dev/docs/admin-api';
    }

    /**
     * Get Shopify support URL
     */
    public function getSupportUrl(): ?string
    {
        return 'https://shopify.dev/support';
    }
}
