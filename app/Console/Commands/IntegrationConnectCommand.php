<?php

namespace App\Console\Commands;

use App\Services\Integration\IntegrationManager;
use Illuminate\Console\Command;

class IntegrationConnectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:connect 
                            {integration : The integration name (shopify, mailchimp, stripe, etc.)}
                            {--store= : Store name for e-commerce integrations}
                            {--list= : List ID for marketing integrations}
                            {--webhook-sync : Enable webhook synchronization}
                            {--test : Test connection without saving}
                            {--config=* : Additional configuration options}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect to external services with one-click integration';

    protected IntegrationManager $integrationManager;

    public function __construct(IntegrationManager $integrationManager)
    {
        parent::__construct();
        $this->integrationManager = $integrationManager;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $integrationName = $this->argument('integration');

        $this->info("🚀 Laravel CMS Integration Hub");
        $this->line("Connecting to: <fg=cyan>{$integrationName}</>");
        $this->newLine();

        // Get integration instance
        $integration = $this->integrationManager->getIntegration($integrationName);

        if (!$integration) {
            $this->error("❌ Integration '{$integrationName}' not found!");
            $this->line("Available integrations:");

            foreach ($this->integrationManager->getAvailableIntegrations() as $name => $class) {
                $this->line("  • {$name}");
            }

            return Command::FAILURE;
        }

        $this->info("📋 Integration: {$integration->getDisplayName()}");
        $this->line("📝 Description: {$integration->getDescription()}");
        $this->line("🏷️  Category: {$integration->getCategory()}");
        $this->newLine();

        // Collect configuration
        $config = $this->collectConfiguration($integration);

        if (empty($config)) {
            $this->warn("⚠️  Configuration cancelled by user");
            return Command::FAILURE;
        }

        // Test connection if requested
        if ($this->option('test')) {
            $this->info("🧪 Testing connection...");
            $testResult = $this->integrationManager->testConnection($integrationName, $config);

            if ($testResult['success']) {
                $this->info("✅ Connection test successful!");
                return Command::SUCCESS;
            } else {
                $this->error("❌ Connection test failed: {$testResult['message']}");
                return Command::FAILURE;
            }
        }

        // Connect to integration
        $this->info("🔗 Connecting to {$integration->getDisplayName()}...");
        $result = $this->integrationManager->connect($integrationName, $config);

        if ($result['success']) {
            $this->info("✅ {$result['message']}");

            // Show available operations
            $operations = $integration->getAvailableOperations();
            if (!empty($operations)) {
                $this->newLine();
                $this->info("🎯 Available operations:");
                foreach ($operations as $operation) {
                    $this->line("  • {$operation}");
                }
            }

            // Show next steps
            $this->newLine();
            $this->info("🎉 Integration setup complete!");
            $this->line("Next steps:");
            $this->line("  • Visit /admin/integrations to manage your connections");
            $this->line("  • Use 'php artisan cms:sync {$integrationName}' to sync data");
            $this->line("  • Check logs for detailed activity information");

            return Command::SUCCESS;
        } else {
            $this->error("❌ {$result['message']}");
            return Command::FAILURE;
        }
    }

    /**
     * Collect configuration from user
     */
    protected function collectConfiguration($integration): array
    {
        $requiredFields = $integration->getRequiredFields();
        $config = [];

        $this->info("⚙️  Configuration Setup");
        $this->line("Please provide the required configuration:");
        $this->newLine();

        foreach ($requiredFields as $field => $rules) {
            $label = ucwords(str_replace(['_', '-'], ' ', $field));
            $description = is_array($rules) ? ($rules['description'] ?? '') : '';

            if ($description) {
                $this->line("<fg=yellow>{$description}</>");
            }

            // Handle special cases based on integration name
            $value = $this->getFieldValue($field, $label, $rules);

            if ($value === false) {
                return []; // User cancelled
            }

            $config[$field] = $value;
        }

        // Add command line options
        if ($this->option('store')) {
            $config['store'] = $this->option('store');
        }

        if ($this->option('list')) {
            $config['list_id'] = $this->option('list');
        }

        if ($this->option('webhook-sync')) {
            $config['webhook_sync'] = true;
        }

        // Add additional config options
        $additionalConfig = $this->option('config');
        foreach ($additionalConfig as $configItem) {
            if (strpos($configItem, '=') !== false) {
                [$key, $value] = explode('=', $configItem, 2);
                $config[$key] = $value;
            }
        }

        return $config;
    }

    /**
     * Get field value from user
     */
    protected function getFieldValue(string $field, string $label, $rules): mixed
    {
        $isPassword = str_contains($field, 'password') || str_contains($field, 'secret') || str_contains($field, 'key');
        $isRequired = is_array($rules) ? ($rules['required'] ?? true) : true;

        if ($isPassword) {
            return $this->secret("{$label}:");
        }

        if ($isRequired) {
            return $this->ask("{$label}:");
        }

        return $this->ask("{$label} (optional):", '');
    }
}
