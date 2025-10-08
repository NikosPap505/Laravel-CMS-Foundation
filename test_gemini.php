<?php

require 'vendor/autoload.php';

use Gemini;

// Get API key from environment
$apiKey = getenv('GEMINI_API_KEY');

if (!$apiKey) {
    echo "Please set GEMINI_API_KEY environment variable\n";
    exit(1);
}

try {
    echo "Testing Gemini API connection...\n";
    echo "API Key: " . substr($apiKey, 0, 10) . "...\n\n";

    $client = Gemini::client($apiKey);

    echo "Available models:\n";
    echo "================\n";

    // List all available models
    $models = $client->models()->list();

    foreach ($models->models as $model) {
        echo "- " . $model->name . "\n";
        echo "  Display Name: " . ($model->displayName ?? 'N/A') . "\n";
        echo "  Description: " . (isset($model->description) ? substr($model->description, 0, 80) . '...' : 'N/A') . "\n";
        echo "  Supported Methods: " . implode(', ', $model->supportedGenerationMethods ?? []) . "\n";
        echo "\n";
    }

    // Test with a simple model
    echo "\nTesting content generation with different models...\n";
    echo "=================================================\n";

    $testModels = [
        'gemini-1.5-flash-latest',
        'gemini-1.5-pro-latest',
        'gemini-1.5-flash',
        'gemini-1.5-pro',
        'gemini-pro',
        'gemini-pro-1.5',
        'models/gemini-1.5-flash-latest',
        'models/gemini-1.5-pro-latest'
    ];

    foreach ($testModels as $modelName) {
        try {
            echo "Testing model: $modelName\n";
            $model = $client->generativeModel($modelName);
            $response = $model->generateContent('Say hello in one word');
            echo "✅ SUCCESS: " . $response->text() . "\n";
            echo "Working model found: $modelName\n";
            break;
        } catch (Exception $e) {
            echo "❌ FAILED: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
