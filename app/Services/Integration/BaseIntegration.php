<?php

namespace App\Services\Integration;

use App\Services\Integration\Contracts\IntegrationInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

abstract class BaseIntegration implements IntegrationInterface
{
    protected string $name;
    protected string $description;
    protected string $category;
    protected array $config = [];
    protected bool $connected = false;
    protected array $requiredFields = [];

    public function __construct()
    {
        $this->loadConfiguration();
    }

    /**
     * Load integration configuration from database
     */
    protected function loadConfiguration(): void
    {
        $integration = \App\Models\Integration::where('name', $this->getName())->first();
        if ($integration) {
            $this->config = $integration->config ?? [];
            $this->connected = $integration->is_connected ?? false;
        }
    }

    /**
     * Save integration configuration
     */
    protected function saveConfiguration(): void
    {
        $integration = \App\Models\Integration::updateOrCreate(
            ['name' => $this->getName()],
            [
                'config' => $this->config,
                'is_connected' => $this->connected,
                'last_sync' => now(),
                'status' => $this->getStatus()
            ]
        );
    }

    /**
     * Validate configuration
     */
    protected function validateConfig(array $config): array
    {
        $errors = [];

        foreach ($this->getRequiredFields() as $field => $rules) {
            if (!isset($config[$field]) || empty($config[$field])) {
                $errors[$field] = "Field '{$field}' is required";
            }
        }

        return $errors;
    }

    /**
     * Log integration activity
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        Log::channel('integrations')->{$level}(
            "[{$this->getName()}] {$message}",
            array_merge($context, [
                'integration' => $this->getName(),
                'category' => $this->getCategory()
            ])
        );
    }

    /**
     * Cache integration data
     */
    protected function cache(string $key, $data, int $ttl = 3600): void
    {
        Cache::put("integration.{$this->getName()}.{$key}", $data, $ttl);
    }

    /**
     * Get cached integration data
     */
    protected function getCached(string $key, $default = null)
    {
        return Cache::get("integration.{$this->getName()}.{$key}", $default);
    }

    /**
     * Clear integration cache
     */
    protected function clearCache(): void
    {
        Cache::forget("integration.{$this->getName()}");
    }

    /**
     * Get integration display name
     */
    public function getDisplayName(): string
    {
        return ucfirst(str_replace('_', ' ', $this->getName()));
    }

    /**
     * Get integration status
     */
    public function getStatus(): string
    {
        if (!$this->connected) {
            return 'disconnected';
        }

        try {
            return $this->testConnection($this->config) ? 'connected' : 'error';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Get integration health metrics
     */
    public function getHealthMetrics(): array
    {
        return [
            'status' => $this->getStatus(),
            'last_sync' => $this->getCached('last_sync'),
            'error_count' => $this->getCached('error_count', 0),
            'success_rate' => $this->getCached('success_rate', 100),
            'response_time' => $this->getCached('response_time', 0)
        ];
    }

    /**
     * Update health metrics
     */
    protected function updateHealthMetrics(bool $success, float $responseTime): void
    {
        $errorCount = $this->getCached('error_count', 0);
        $successCount = $this->getCached('success_count', 0);

        if ($success) {
            $successCount++;
        } else {
            $errorCount++;
        }

        $total = $successCount + $errorCount;
        $successRate = $total > 0 ? ($successCount / $total) * 100 : 100;

        $this->cache('error_count', $errorCount);
        $this->cache('success_count', $successCount);
        $this->cache('success_rate', $successRate);
        $this->cache('response_time', $responseTime);
        $this->cache('last_sync', now()->toISOString());
    }

    /**
     * Get integration analytics
     */
    public function getAnalytics(): array
    {
        return [
            'status' => $this->getStatus(),
            'last_sync' => $this->getCached('last_sync'),
            'error_count' => $this->getCached('error_count', 0),
            'success_rate' => $this->getCached('success_rate', 100),
            'response_time' => $this->getCached('response_time', 0)
        ];
    }

    /**
     * Get integration icon
     */
    public function getIcon(): string
    {
        $icons = [
            'ecommerce' => '🛒',
            'marketing' => '📧',
            'analytics' => '📊',
            'social' => '📱',
            'crm' => '👥',
            'payment' => '💳',
            'communication' => '💬'
        ];

        return $icons[$this->getCategory()] ?? '🔗';
    }

    /**
     * Get integration features
     */
    public function getFeatures(): array
    {
        return [
            'Data synchronization',
            'Real-time updates',
            'Error handling',
            'Health monitoring'
        ];
    }

    /**
     * Get integration version
     */
    public function getVersion(): ?string
    {
        return '1.0.0';
    }

    /**
     * Get documentation URL
     */
    public function getDocumentationUrl(): ?string
    {
        return null;
    }

    /**
     * Get support URL
     */
    public function getSupportUrl(): ?string
    {
        return null;
    }
}
