<?php

namespace Tests\Unit;

use App\Services\AI\AIService;
use Tests\TestCase;
use Exception;

class AIServiceTest extends TestCase
{
    protected AIService $aiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aiService = app(AIService::class);
    }

    public function test_ai_service_can_be_instantiated()
    {
        $this->assertInstanceOf(AIService::class, $this->aiService);
    }

    public function test_ai_availability_check_works()
    {
        // This should return false in testing environment without API key
        $available = $this->aiService->isAvailable();
        $this->assertIsBool($available);
    }

    public function test_ai_features_list_is_array()
    {
        $features = $this->aiService->getAvailableFeatures();
        $this->assertIsArray($features);
    }

    public function test_supported_tones_returns_array()
    {
        $tones = $this->aiService->getSupportedTones();
        $this->assertIsArray($tones);
        
        // Should contain expected tones
        $expectedTones = ['professional', 'casual', 'friendly', 'authoritative', 'conversational', 'technical', 'creative'];
        foreach ($expectedTones as $tone) {
            $this->assertContains($tone, $tones);
        }
    }

    public function test_supported_formats_returns_array()
    {
        $formats = $this->aiService->getSupportedFormats();
        $this->assertIsArray($formats);
        
        // Should contain expected formats
        $expectedFormats = ['blog_post', 'article', 'product_description', 'meta_description'];
        foreach ($expectedFormats as $format) {
            $this->assertContains($format, $formats);
        }
    }

    public function test_content_analysis_basic_functionality()
    {
        $content = "This is a sample blog post content. It has multiple sentences and paragraphs. This helps test the analysis functionality.";
        
        $analysis = $this->aiService->analyzeContent($content);
        
        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('word_count', $analysis);
        $this->assertArrayHasKey('character_count', $analysis);
        $this->assertArrayHasKey('reading_time', $analysis);
        
        $this->assertIsInt($analysis['word_count']);
        $this->assertIsInt($analysis['character_count']);
        $this->assertIsInt($analysis['reading_time']);
        
        $this->assertGreaterThan(0, $analysis['word_count']);
        $this->assertGreaterThan(0, $analysis['character_count']);
        $this->assertGreaterThan(0, $analysis['reading_time']);
    }

    public function test_ai_methods_throw_exception_when_not_configured()
    {
        // These should throw exceptions when AI is not configured
        $this->expectException(Exception::class);
        
        $this->aiService->generateBlogPost('Test Topic');
    }

    public function test_ai_methods_handle_missing_configuration_gracefully()
    {
        // Test that methods don't crash the application
        try {
            $this->aiService->generateMetaDescription('Test Title');
            $this->fail('Should have thrown an exception');
        } catch (Exception $e) {
            $this->assertStringContainsString('AI service not configured', $e->getMessage());
        }
    }

    public function test_usage_stats_returns_array()
    {
        $stats = $this->aiService->getUsageStats();
        $this->assertIsArray($stats);
    }

    public function test_safe_execute_returns_fallback_on_error()
    {
        // Test the protected method through reflection
        $reflection = new \ReflectionClass($this->aiService);
        $method = $reflection->getMethod('safeExecute');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->aiService, function() {
            throw new Exception('Test error');
        }, 'fallback_value');
        
        $this->assertEquals('fallback_value', $result);
    }

    public function test_seo_optimization_handles_empty_data()
    {
        $postData = [
            'title' => 'Test Title',
            'body' => 'Test content for SEO optimization',
        ];
        
        $optimized = $this->aiService->optimizePostForSEO($postData);
        
        // Should return original data when AI is not available
        $this->assertIsArray($optimized);
        $this->assertEquals($postData['title'], $optimized['title']);
        $this->assertEquals($postData['body'], $optimized['body']);
    }

    public function test_reading_time_calculation()
    {
        // Test with known content length
        $shortContent = str_repeat('word ', 50); // ~50 words
        $longContent = str_repeat('word ', 500); // ~500 words
        
        $shortAnalysis = $this->aiService->analyzeContent($shortContent);
        $longAnalysis = $this->aiService->analyzeContent($longContent);
        
        $this->assertEquals(1, $shortAnalysis['reading_time']); // Should be 1 minute minimum
        $this->assertGreaterThan($shortAnalysis['reading_time'], $longAnalysis['reading_time']);
    }

    public function test_configuration_methods()
    {
        // Test that configuration methods work without errors
        $this->assertIsArray($this->aiService->getSupportedLanguages());
        
        // Test feature flags work
        $features = $this->aiService->getAvailableFeatures();
        $this->assertIsArray($features);
    }

    public function test_ai_service_handles_large_content()
    {
        // Test with large content to ensure it doesn't crash
        $largeContent = str_repeat('This is a test sentence with multiple words. ', 1000);
        
        $analysis = $this->aiService->analyzeContent($largeContent);
        
        $this->assertIsArray($analysis);
        $this->assertGreaterThan(1000, $analysis['word_count']);
        $this->assertGreaterThan(5, $analysis['reading_time']);
    }
}