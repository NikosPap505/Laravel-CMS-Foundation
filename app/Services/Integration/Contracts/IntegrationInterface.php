<?php

namespace App\Services\Integration\Contracts;

interface IntegrationInterface
{
    /**
     * Get the integration name
     */
    public function getName(): string;

    /**
     * Get the integration display name
     */
    public function getDisplayName(): string;

    /**
     * Get the integration description
     */
    public function getDescription(): string;

    /**
     * Get the integration category
     */
    public function getCategory(): string;

    /**
     * Get required configuration fields
     */
    public function getRequiredFields(): array;

    /**
     * Test the integration connection
     */
    public function testConnection(array $config): bool;

    /**
     * Connect to the integration
     */
    public function connect(array $config): array;

    /**
     * Disconnect from the integration
     */
    public function disconnect(): bool;

    /**
     * Get integration status
     */
    public function getStatus(): string;

    /**
     * Sync data from the integration
     */
    public function sync(array $options = []): array;

    /**
     * Get available sync operations
     */
    public function getAvailableOperations(): array;

    /**
     * Execute a specific operation
     */
    public function executeOperation(string $operation, array $data = []): array;

    /**
     * Get integration analytics
     */
    public function getAnalytics(): array;

    /**
     * Get integration icon
     */
    public function getIcon(): string;

    /**
     * Get integration features
     */
    public function getFeatures(): array;

    /**
     * Get integration version
     */
    public function getVersion(): ?string;

    /**
     * Get documentation URL
     */
    public function getDocumentationUrl(): ?string;

    /**
     * Get support URL
     */
    public function getSupportUrl(): ?string;
}
