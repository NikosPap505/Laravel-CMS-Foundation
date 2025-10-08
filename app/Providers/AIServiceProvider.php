<?php

namespace App\Providers;

use App\Services\AI\AIService;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OpenAIProvider;
use App\Services\AI\Providers\GeminiProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Foundation\Application;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the AI service as a singleton
        $this->app->singleton(AIService::class, function (Application $app) {
            return new AIService();
        });

        // Register the default AI provider
        $this->app->bind(AIProviderInterface::class, function (Application $app) {
            $provider = config('ai.default', 'gemini');
            
            return match ($provider) {
                'openai' => new OpenAIProvider(),
                'gemini' => new GeminiProvider(),
                default => throw new \InvalidArgumentException("Unsupported AI provider: {$provider}")
            };
        });

        // Create an alias for easier access
        $this->app->alias(AIService::class, 'ai');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/ai.php', 'ai');

        // Publish configuration if running in console
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/ai.php' => config_path('ai.php'),
            ], 'ai-config');
        }

        // Register Blade directives for AI features
        $this->registerBladeDirectives();

        // Register view composers
        $this->registerViewComposers();
    }

    /**
     * Register custom Blade directives for AI features.
     */
    protected function registerBladeDirectives(): void
    {
        // @aiAvailable directive to check if AI is configured
        Blade::if('aiAvailable', function () {
            return app(AIService::class)->isAvailable();
        });

        // @aiFeature directive to check if specific AI feature is enabled
        Blade::if('aiFeature', function (string $feature) {
            $availableFeatures = app(AIService::class)->getAvailableFeatures();
            return in_array($feature, $availableFeatures);
        });

        // @aiGenerateButton directive for easy AI generation buttons
        Blade::directive('aiGenerateButton', function ($expression) {
            return "<?php echo view('components.ai.generate-button', {$expression})->render(); ?>";
        });

        // @aiStats directive to display AI usage stats
        Blade::directive('aiStats', function () {
            return "<?php 
                try {
                    \$stats = app(\\App\\Services\\AI\\AIService::class)->getUsageStats();
                    echo view('components.ai.stats', compact('stats'))->render();
                } catch (Exception \$e) {
                    // Silently fail if AI is not available
                }
            ?>";
        });
    }

    /**
     * Register view composers for AI-related views.
     */
    protected function registerViewComposers(): void
    {
        // Share AI availability status with admin views
        view()->composer('admin.*', function ($view) {
            try {
                $aiService = app(AIService::class);
                $view->with([
                    'aiAvailable' => $aiService->isAvailable(),
                    'aiFeatures' => $aiService->getAvailableFeatures(),
                    'aiTones' => $aiService->getSupportedTones(),
                    'aiFormats' => $aiService->getSupportedFormats(),
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'aiAvailable' => false,
                    'aiFeatures' => [],
                    'aiTones' => [],
                    'aiFormats' => [],
                ]);
            }
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            AIService::class,
            AIProviderInterface::class,
            'ai',
        ];
    }
}