@extends('layouts.admin')

@section('title', 'AI Assistant')
@section('subtitle', 'Generate content with artificial intelligence')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-text-primary flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    AI Assistant
                </h1>
                <p class="text-text-secondary">Generate and optimize content with AI assistance</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($aiAvailable)
                <div class="text-right">
                    <div class="flex items-center text-green-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ strtoupper(config('ai.default', 'gemini')) }} Active
                    </div>
                </div>
                @else
                <div class="text-right">
                    <div class="flex items-center text-yellow-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Not Configured
                    </div>
                </div>
                @endif
                <button type="button" class="bg-surface border border-border text-text-secondary px-3 py-1 rounded text-sm hover:bg-background transition" onclick="checkAIStatus()">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Check Status
                </button>
            </div>
        </div>

        <!-- AI Assistant Categories -->
        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-6 border border-pink-200 dark:border-pink-800 mb-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-pink-500 text-white rounded-lg mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">AI Management</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Monitor and track AI usage and analytics</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('admin.ai.analytics') }}" class="flex items-center p-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group border border-gray-200 dark:border-gray-700">
                    <div class="p-2 bg-indigo-500 text-white rounded-lg mr-4 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Analytics</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">AI usage insights and performance metrics</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admin.ai.usage-page') }}" class="flex items-center p-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group border border-gray-200 dark:border-gray-700">
                    <div class="p-2 bg-blue-500 text-white rounded-lg mr-4 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Usage</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Track AI usage patterns and statistics</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @if(!$aiAvailable)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-yellow-800 font-medium">AI Service Not Available</h3>
                    <p class="text-yellow-700 mt-1">
                        The AI assistant is not configured. Please add your API key to the .env file and restart the application.
                    </p>
                    <p class="text-yellow-600 text-sm mt-2">
                        Current provider: <strong>{{ strtoupper(config('ai.default', 'gemini')) }}</strong><br>
                        @if(config('ai.default', 'gemini') === 'gemini')
                        Set <code class="bg-yellow-100 px-1 rounded">GEMINI_API_KEY</code> in your .env file.<br>
                        Get your API key from: <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-blue-600 underline">Google AI Studio</a>
                        @else
                        Set <code class="bg-yellow-100 px-1 rounded">OPENAI_API_KEY</code> in your .env file.<br>
                        Get your API key from: <a href="https://platform.openai.com/" target="_blank" class="text-blue-600 underline">OpenAI Platform</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif


        <!-- Coming Soon -->
        <div class="bg-surface border border-border rounded-lg p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-text-primary mb-3">AI Tools Coming Soon</h3>
                <p class="text-text-secondary mb-6">We're working on a new design for AI-powered content creation tools. Stay tuned for blog generation, content optimization, SEO tools, and social media features.</p>
                <div class="flex items-center justify-center space-x-4 text-sm text-text-secondary">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        Blog Generation
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        Content Optimizer
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                        SEO Tools
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-pink-500 rounded-full mr-2"></div>
                        Social Media
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // AI page is ready - tools coming soon
});

function checkAIStatus() {
    @if($aiAvailable)
        alert('✅ AI Service Status: Available\n\nProvider: {{ strtoupper(config("ai.default", "gemini")) }}\nThe AI service is properly configured and ready to use!');
    @else
        const provider = '{{ config("ai.default", "gemini") }}';
        const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
        const url = provider === 'gemini' ? 'https://aistudio.google.com/app/apikey' : 'https://platform.openai.com/';
        alert(`⚠️ AI Service Status: Not Available\n\nCurrent Provider: ${provider.toUpperCase()}\nPlease add your API key to the .env file:\n${keyName}=your_key_here\n\nGet your API key from: ${url}\n\nThen restart the application to enable AI features.`);
    @endif
}
</script>
@endsection