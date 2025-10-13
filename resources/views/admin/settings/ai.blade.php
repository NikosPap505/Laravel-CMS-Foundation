@extends('layouts.admin')

@section('title', 'AI Configuration')
@section('subtitle', 'AI provider settings and content generation')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center text-sm font-medium text-text-secondary hover:text-accent">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Settings
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">AI Configuration</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.settings.store.ai') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- AI Provider Selection --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">AI Provider</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach(['openai' => 'OpenAI', 'gemini' => 'Google Gemini', 'anthropic' => 'Anthropic Claude', 'local' => 'Local AI'] as $provider => $name)
                                    <label for="provider_{{ $provider }}" class="provider-option {{ old('provider', setting('ai.provider', config('ai.default', 'gemini'))) == $provider ? 'ring-2 ring-accent' : '' }} block cursor-pointer">
                                        <input type="radio" name="provider" id="provider_{{ $provider }}" value="{{ $provider }}" 
                                               {{ old('provider', setting('ai.provider', config('ai.default', 'gemini'))) == $provider ? 'checked' : '' }}
                                               class="sr-only" onchange="selectProvider('{{ $provider }}', this)">
                                        
                                        <div class="border border-border rounded-lg p-4 hover:border-accent/50 transition-colors focus-within:ring-2 focus-within:ring-accent">
                                            <div class="flex items-center mb-2">
                                                @switch($provider)
                                                    @case('openai')
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M22.2819 9.8211a5.9847 5.9847 0 0 0-.5157-4.9108 6.0462 6.0462 0 0 0-6.5098-2.9A6.0651 6.0651 0 0 0 4.9807 4.1818a5.9847 5.9847 0 0 0-3.9977 2.9 6.0462 6.0462 0 0 0 .7427 7.0966 5.98 5.98 0 0 0 .511 4.9107 6.051 6.051 0 0 0 6.5146 2.9001A5.9847 5.9847 0 0 0 13.2599 24a6.0557 6.0557 0 0 0 5.7718-4.2058 5.9894 5.9894 0 0 0 3.9977-2.9001 6.0557 6.0557 0 0 0-.7475-7.0729zm-9.022 12.6081a4.4755 4.4755 0 0 1-2.8764-1.0408l.1419-.0804 4.7783-2.7582a.7948.7948 0 0 0 .3927-.6813v-6.7369l2.02 1.1686a.071.071 0 0 1 .038.052v5.5826a4.504 4.504 0 0 1-4.4945 4.4944zm-9.6607-4.1254a4.4708 4.4708 0 0 1-.5346-3.0137l.142.0852 4.783 2.7582a.7712.7712 0 0 0 .7806 0l5.8428-3.3685v2.3324a.0804.0804 0 0 1-.0332.0615L9.74 19.9502a4.4992 4.4992 0 0 1-6.1408-1.6464zm-2.456-11.2528a4.4708 4.4708 0 0 1 2.3655-1.9728V11.6a.7664.7664 0 0 0 .3879.6765l5.8144 3.3543-2.0201 1.1685a.0757.0757 0 0 1-.071 0L4.26 13.2464a4.4992 4.4992 0 0 1-2.856-5.9951zm16.5963 3.8558L18.1827 8.484 16.1626 7.3154a.0757.0757 0 0 1-.071 0L10.235 4.1818a.7664.7664 0 0 0-.7854 0L3.6068 7.5496v-2.3324a.0804.0804 0 0 1 .0332-.0615L9.74 2.0498a4.4992 4.4992 0 0 1 6.6802 4.66zm2.3655 1.9728a4.4708 4.4708 0 0 1-.5346 3.0137L18.1827 8.484v6.7369a.7948.7948 0 0 0 .3927.6813l4.7783 2.7582.1419.0804a4.504 4.504 0 0 1-2.3655 1.9728z"/>
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('gemini')
                                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('anthropic')
                                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('local')
                                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                                                            </svg>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                            <h4 class="font-medium text-text-primary text-sm">{{ $name }}</h4>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- API Keys --}}
                        <div id="api-keys-section">
                            <h3 class="text-lg font-medium text-text-primary mb-4">API Keys</h3>
                            <div class="space-y-4">
                                <div id="openai-key" class="provider-config" style="display: none;">
                                    <label for="openai_api_key" class="block text-sm font-medium text-text-secondary mb-2">OpenAI API Key</label>
                                    <input type="password" name="openai_api_key" id="openai_api_key" 
                                           value="{{ old('openai_api_key', setting('ai.openai_api_key')) }}"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Get your API key from <a href="https://platform.openai.com/api-keys" target="_blank" class="text-accent hover:underline">OpenAI Platform</a></p>
                                </div>

                                <div id="gemini-key" class="provider-config" style="display: none;">
                                    <label for="gemini_api_key" class="block text-sm font-medium text-text-secondary mb-2">Gemini API Key</label>
                                    <input type="password" name="gemini_api_key" id="gemini_api_key" 
                                           value="{{ old('gemini_api_key', setting('ai.gemini_api_key')) }}"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Get your API key from <a href="https://makersuite.google.com/app/apikey" target="_blank" class="text-accent hover:underline">Google AI Studio</a></p>
                                </div>

                                <div id="anthropic-key" class="provider-config" style="display: none;">
                                    <label for="anthropic_api_key" class="block text-sm font-medium text-text-secondary mb-2">Anthropic API Key</label>
                                    <input type="password" name="anthropic_api_key" id="anthropic_api_key" 
                                           value="{{ old('anthropic_api_key', setting('ai.anthropic_api_key')) }}"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Get your API key from <a href="https://console.anthropic.com/" target="_blank" class="text-accent hover:underline">Anthropic Console</a></p>
                                </div>
                            </div>
                        </div>

                        {{-- AI Features --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">AI Features</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="content_generation" id="content_generation" value="1" 
                                               {{ old('content_generation', setting('ai.content_generation', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="content_generation" class="ml-2 block text-sm text-text-primary">
                                            Content Generation
                                        </label>
                                    </div>
                                    <p class="text-xs text-text-secondary ml-6">Enable AI-powered blog post and content generation</p>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="seo_optimization" id="seo_optimization" value="1" 
                                               {{ old('seo_optimization', setting('ai.seo_optimization', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="seo_optimization" class="ml-2 block text-sm text-text-primary">
                                            SEO Optimization
                                        </label>
                                    </div>
                                    <p class="text-xs text-text-secondary ml-6">Automatically optimize content for search engines</p>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="auto_tagging" id="auto_tagging" value="1" 
                                               {{ old('auto_tagging', setting('ai.auto_tagging', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="auto_tagging" class="ml-2 block text-sm text-text-primary">
                                            Auto Tagging
                                        </label>
                                    </div>
                                    <p class="text-xs text-text-secondary ml-6">Automatically generate tags for posts</p>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="content_improvement" id="content_improvement" value="1" 
                                               {{ old('content_improvement', setting('ai.content_improvement', true)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="content_improvement" class="ml-2 block text-sm text-text-primary">
                                            Content Improvement
                                        </label>
                                    </div>
                                    <p class="text-xs text-text-secondary ml-6">Suggest improvements to existing content</p>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="translation" id="translation" value="1" 
                                               {{ old('translation', setting('ai.translation', false)) ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                        <label for="translation" class="ml-2 block text-sm text-text-primary">
                                            Translation
                                        </label>
                                    </div>
                                    <p class="text-xs text-text-secondary ml-6">Translate content to multiple languages</p>
                                </div>
                            </div>
                        </div>

                        {{-- Rate Limiting --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Rate Limiting</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="rate_limiting_enabled" id="rate_limiting_enabled" value="1" 
                                           {{ old('rate_limiting_enabled', setting('ai.rate_limiting_enabled', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="rate_limiting_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable Rate Limiting
                                    </label>
                                </div>

                                <div>
                                    <label for="max_requests_per_hour" class="block text-sm font-medium text-text-secondary mb-2">Max Requests Per Hour</label>
                                    <input type="number" name="max_requests_per_hour" id="max_requests_per_hour" 
                                           value="{{ old('max_requests_per_hour', setting('ai.max_requests_per_hour', 100)) }}"
                                           min="1" max="1000"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Maximum AI requests per hour per user</p>
                                </div>
                            </div>
                        </div>

                        {{-- Usage Statistics --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Usage Statistics</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-accent">{{ $aiConfig['rate_limiting']['max_requests_per_day'] ?? '500' }}</div>
                                        <div class="text-sm text-text-secondary">Requests Today</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-accent">${{ number_format(($aiConfig['rate_limiting']['cost_limit_per_day'] ?? 10), 2) }}</div>
                                        <div class="text-sm text-text-secondary">Daily Cost Limit</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-accent">{{ ucfirst($aiConfig['default'] ?? 'gemini') }}</div>
                                        <div class="text-sm text-text-secondary">Current Provider</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-border pt-6">
                        <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Save AI Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectProvider(provider, element) {
    // Update radio button
    document.getElementById('provider_' + provider).checked = true;
    
    // Update visual selection
    document.querySelectorAll('.provider-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-accent');
    });
    
    if (element && element.closest('.provider-option')) {
        element.closest('.provider-option').classList.add('ring-2', 'ring-accent');
    }
    
    // Show/hide relevant API key fields
    document.querySelectorAll('.provider-config').forEach(config => {
        config.style.display = 'none';
    });
    
    if (provider !== 'local') {
        const configElement = document.getElementById(provider + '-key');
        if (configElement) {
            configElement.style.display = 'block';
        }
    }
}

// Initialize the form based on current selection
document.addEventListener('DOMContentLoaded', function() {
    const selectedProvider = '{{ old("provider", setting("ai.provider", config("ai.default", "gemini"))) }}';
    if (selectedProvider) {
        const providerElement = document.getElementById('provider_' + selectedProvider);
        if (providerElement) {
            providerElement.checked = true;
            selectProvider(selectedProvider, providerElement);
        }
    }
});
</script>
@endsection
