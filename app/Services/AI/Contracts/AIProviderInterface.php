<?php

namespace App\Services\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Generate content based on a prompt and parameters.
     *
     * @param string $prompt
     * @param array $parameters
     * @return string
     */
    public function generateContent(string $prompt, array $parameters = []): string;

    /**
     * Generate a blog post about a specific topic.
     *
     * @param string $topic
     * @param array $options
     * @return array
     */
    public function generateBlogPost(string $topic, array $options = []): array;

    /**
     * Generate SEO-optimized meta description.
     *
     * @param string $title
     * @param string $content
     * @param array $options
     * @return string
     */
    public function generateMetaDescription(string $title, string $content = '', array $options = []): string;

    /**
     * Generate SEO-optimized title suggestions.
     *
     * @param string $topic
     * @param array $options
     * @return array
     */
    public function generateTitleSuggestions(string $topic, array $options = []): array;

    /**
     * Improve existing content for readability and SEO.
     *
     * @param string $content
     * @param array $options
     * @return array
     */
    public function improveContent(string $content, array $options = []): array;

    /**
     * Generate tags for content.
     *
     * @param string $content
     * @param array $options
     * @return array
     */
    public function generateTags(string $content, array $options = []): array;

    /**
     * Generate alt text for images.
     *
     * @param string $imageUrl
     * @param string $context
     * @return string
     */
    public function generateImageAltText(string $imageUrl, string $context = ''): string;

    /**
     * Translate content to another language.
     *
     * @param string $content
     * @param string $targetLanguage
     * @param array $options
     * @return string
     */
    public function translateContent(string $content, string $targetLanguage, array $options = []): string;

    /**
     * Analyze content sentiment.
     *
     * @param string $content
     * @return array
     */
    public function analyzeSentiment(string $content): array;

    /**
     * Generate product description.
     *
     * @param string $productName
     * @param array $features
     * @param array $options
     * @return string
     */
    public function generateProductDescription(string $productName, array $features = [], array $options = []): string;

    /**
     * Generate social media post.
     *
     * @param string $content
     * @param string $platform
     * @param array $options
     * @return string
     */
    public function generateSocialMediaPost(string $content, string $platform = 'twitter', array $options = []): string;

    /**
     * Check if the provider is available and configured.
     *
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Get the provider's configuration.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Get usage statistics for the provider.
     *
     * @return array
     */
    public function getUsageStats(): array;
}