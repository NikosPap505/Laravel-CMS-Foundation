@extends('layouts.app')

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
            <div>
                <button type="button" class="bg-surface border border-border text-text-secondary px-3 py-1 rounded text-sm hover:bg-background transition" onclick="checkAIStatus()">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Check Status
                </button>
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

        @if($aiAvailable)
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-green-800 font-medium">AI Service Active - {{ strtoupper(config('ai.default', 'gemini')) }}</h3>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-green-700">
                                <strong>Status:</strong> <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-1">Operational</span>
                            </p>
                            <p class="text-sm text-green-700 mt-2"><strong>Available Features:</strong></p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($features as $feature)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', $feature)) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            @if(count($usageStats) > 0)
                            <p class="text-sm text-green-700"><strong>Recent Usage:</strong></p>
                            <p class="text-xs text-green-600">{{ count($usageStats) }} requests in current session</p>
                            @else
                            <p class="text-sm text-green-700"><strong>Usage:</strong> No recent activity</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- AI Tools Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Blog Post Generator -->
            <div class="bg-surface border border-border rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                    <h3 class="text-white font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Blog Post Generator
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-text-secondary mb-4">Generate complete blog posts with AI assistance, including title, content, meta description, and tags.</p>
                    <form id="blogPostForm" class="space-y-4">
                        <div>
                            <label for="blogTopic" class="block text-sm font-medium text-text-primary mb-1">Topic</label>
                            <input type="text" class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="blogTopic" placeholder="e.g., Laravel Security Best Practices" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="blogTone" class="block text-sm font-medium text-text-primary mb-1">Tone</label>
                                <select class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="blogTone">
                                    <option value="professional">Professional</option>
                                    <option value="casual">Casual</option>
                                    <option value="friendly">Friendly</option>
                                    <option value="authoritative">Authoritative</option>
                                    <option value="technical">Technical</option>
                                </select>
                            </div>
                            <div>
                                <label for="wordCount" class="block text-sm font-medium text-text-primary mb-1">Word Count</label>
                                <input type="number" class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="wordCount" value="800" min="100" max="3000">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent-hover transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428l-7.071 7.071a2 2 0 01-2.828 0l-7.071-7.071a2 2 0 010-2.828l7.071-7.071a2 2 0 012.828 0l7.071 7.071a2 2 0 010 2.828z"/>
                            </svg>
                            Generate Blog Post
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Optimizer -->
            <div class="bg-surface border border-border rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                    <h3 class="text-white font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Content Optimizer
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-text-secondary mb-4">Improve existing content for better readability, SEO, and engagement.</p>
                    <form id="contentOptimizerForm" class="space-y-4">
                        <div>
                            <label for="contentToImprove" class="block text-sm font-medium text-text-primary mb-1">Content to Improve</label>
                            <textarea class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="contentToImprove" rows="4" placeholder="Paste your content here..." required></textarea>
                        </div>
                        <div>
                            <label for="improvementFocus" class="block text-sm font-medium text-text-primary mb-1">Focus Area</label>
                            <select class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="improvementFocus">
                                <option value="all">All Areas</option>
                                <option value="grammar">Grammar & Clarity</option>
                                <option value="seo">SEO Optimization</option>
                                <option value="readability">Readability</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                            </svg>
                            Improve Content
                        </button>
                    </form>
                </div>
            </div>

            <!-- SEO Tools -->
            <div class="bg-surface border border-border rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                    <h3 class="text-white font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        SEO Tools
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-text-secondary mb-4">Generate meta descriptions, title suggestions, and SEO tags.</p>
                    
                    <!-- Meta Description Generator -->
                    <div class="mb-6">
                        <h4 class="font-medium text-text-primary mb-2">Meta Description</h4>
                        <form id="metaDescriptionForm" class="space-y-2">
                            <input type="text" class="w-full px-3 py-2 border border-border rounded text-sm" id="metaTitle" placeholder="Page/Post Title" required>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                                Generate Meta Description
                            </button>
                        </form>
                    </div>

                    <!-- Title Suggestions -->
                    <div class="mb-6">
                        <h4 class="font-medium text-text-primary mb-2">Title Suggestions</h4>
                        <form id="titleSuggestionsForm" class="space-y-2">
                            <input type="text" class="w-full px-3 py-2 border border-border rounded text-sm" id="titleTopic" placeholder="Topic for titles" required>
                            <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                                Generate Titles
                            </button>
                        </form>
                    </div>

                    <!-- Tag Generator -->
                    <div>
                        <h4 class="font-medium text-text-primary mb-2">Content Tags</h4>
                        <form id="tagsForm" class="space-y-2">
                            <textarea class="w-full px-3 py-2 border border-border rounded text-sm" id="tagContent" rows="2" placeholder="Content for tag generation" required></textarea>
                            <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                                Generate Tags
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-surface border border-border rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 px-6 py-4">
                    <h3 class="text-white font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                        Social Media
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-text-secondary mb-4">Generate engaging social media posts for different platforms.</p>
                    <form id="socialMediaForm" class="space-y-4">
                        <div>
                            <label for="socialContent" class="block text-sm font-medium text-text-primary mb-1">Source Content</label>
                            <textarea class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="socialContent" rows="3" placeholder="Content to adapt for social media" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="socialPlatform" class="block text-sm font-medium text-text-primary mb-1">Platform</label>
                                <select class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="socialPlatform">
                                    <option value="twitter">Twitter</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="instagram">Instagram</option>
                                </select>
                            </div>
                            <div>
                                <label for="socialTone" class="block text-sm font-medium text-text-primary mb-1">Tone</label>
                                <select class="w-full px-3 py-2 border border-border rounded-lg focus:ring-2 focus:ring-accent focus:border-accent" id="socialTone">
                                    <option value="engaging">Engaging</option>
                                    <option value="professional">Professional</option>
                                    <option value="casual">Casual</option>
                                    <option value="promotional">Promotional</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition {{ !$aiAvailable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$aiAvailable ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                            </svg>
                            Generate Social Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div id="aiResultsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="bg-surface border border-border rounded-lg max-w-4xl w-full max-h-screen overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-border">
                <h3 id="aiResultsModalLabel" class="text-lg font-medium text-text-primary">AI Generated Content</h3>
                <button type="button" class="text-text-secondary hover:text-text-primary" onclick="closeModal()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-96" id="aiResultsContent">
                <!-- Results will be loaded here -->
            </div>
            <div class="flex justify-end items-center space-x-2 p-6 border-t border-border">
                <button type="button" class="px-4 py-2 text-text-secondary hover:text-text-primary" onclick="closeModal()">Close</button>
                <button type="button" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent-hover transition" id="copyResultsBtn">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy Results
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Basic AI functionality - simplified for reliability
document.addEventListener('DOMContentLoaded', function() {
    // Add form event listeners
    if (document.getElementById('blogPostForm')) {
        document.getElementById('blogPostForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            @if($aiAvailable)
            const topic = document.getElementById('blogTopic').value;
            const tone = document.getElementById('blogTone').value;
            const wordCount = document.getElementById('wordCount').value;
            try {
                const response = await fetch('{{ route("admin.ai.generate-blog-post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        topic: topic,
                        tone: tone,
                        word_count: parseInt(wordCount)
                    })
                });
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    let html = '';
                    html += '<div class="mb-4"><h4 class="font-medium text-text-primary mb-2">Title</h4><div class="bg-background border border-border rounded p-3">'+(data.title||'')+'</div></div>';
                    html += '<div class="mb-4"><h4 class="font-medium text-text-primary mb-2">Excerpt</h4><div class="bg-background border border-border rounded p-3">'+(data.excerpt||'')+'</div></div>';
                    html += '<div class="mb-4"><h4 class="font-medium text-text-primary mb-2">Meta Description</h4><div class="bg-background border border-border rounded p-3">'+(data.meta_description||'')+'</div></div>';
                    if (data.tags && data.tags.length) {
                        html += '<div class="mb-4"><h4 class="font-medium text-text-primary mb-2">Tags</h4><div class="flex flex-wrap gap-2">'+data.tags.map(t=>`<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${t}</span>`).join('')+'</div></div>';
                    }
                    html += '<div class="mb-4"><h4 class="font-medium text-text-primary mb-2">Content</h4><div class="bg-background border border-border rounded p-3 max-h-80 overflow-y-auto">'+(data.content||'').replace(/\\n/g,'<br>')+'</div></div>';
                    document.getElementById('aiResultsModalLabel').textContent = 'Generated Blog Post';
                    document.getElementById('aiResultsContent').innerHTML = html;
                    document.getElementById('aiResultsModal').classList.remove('hidden');
                } else {
                    alert(result.message || 'Failed to generate blog post');
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            }
            @else
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            const url = provider === 'gemini' ? 'https://aistudio.google.com/app/apikey' : 'https://platform.openai.com/';
            alert(`AI Blog Post Generation\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application.\n\nGet your API key from: ${url}`);
            @endif
        });
    }

    if (document.getElementById('contentOptimizerForm')) {
        document.getElementById('contentOptimizerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            alert(`AI Content Optimization\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application to enable AI features.`);
        });
    }

    if (document.getElementById('metaDescriptionForm')) {
        document.getElementById('metaDescriptionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            alert(`AI Meta Description Generation\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application to enable AI features.`);
        });
    }

    if (document.getElementById('titleSuggestionsForm')) {
        document.getElementById('titleSuggestionsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            alert(`AI Title Generation\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application to enable AI features.`);
        });
    }

    if (document.getElementById('tagsForm')) {
        document.getElementById('tagsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            alert(`AI Tag Generation\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application to enable AI features.`);
        });
    }

    if (document.getElementById('socialMediaForm')) {
        document.getElementById('socialMediaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = '{{ config("ai.default", "gemini") }}';
            const keyName = provider === 'gemini' ? 'GEMINI_API_KEY' : 'OPENAI_API_KEY';
            alert(`AI Social Media Generation\n\nThis feature requires a ${provider.toUpperCase()} API key.\nAdd ${keyName} to your .env file and restart the application to enable AI features.`);
        });
    }
});

function closeModal() {
    document.getElementById('aiResultsModal').classList.add('hidden');
}

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