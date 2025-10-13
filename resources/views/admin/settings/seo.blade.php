@extends('layouts.admin')

@section('title', 'SEO Settings')
@section('subtitle', 'Search engine optimization and meta tags')

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">SEO Settings</span>
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
                <form action="{{ route('admin.settings.store.seo') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- Meta Tags --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Meta Tags</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="meta_title_template" class="block text-sm font-medium text-text-secondary mb-2">Meta Title Template</label>
                                    <input type="text" name="meta_title_template" id="meta_title_template" 
                                           value="{{ old('meta_title_template', setting('seo.meta_title_template', '{title} | {site_name}')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Use {title} for page title, {site_name} for site name. Max 60 characters recommended.</p>
                                </div>

                                <div>
                                    <label for="meta_description_template" class="block text-sm font-medium text-text-secondary mb-2">Meta Description Template</label>
                                    <textarea name="meta_description_template" id="meta_description_template" rows="3" 
                                              class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('meta_description_template', setting('seo.meta_description_template')) }}</textarea>
                                    <p class="mt-1 text-xs text-text-secondary">Default meta description for pages without specific descriptions. Max 160 characters recommended.</p>
                                </div>

                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium text-text-secondary mb-2">Default Meta Keywords</label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" 
                                           value="{{ old('meta_keywords', setting('seo.meta_keywords')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="keyword1, keyword2, keyword3">
                                    <p class="mt-1 text-xs text-text-secondary">Comma-separated keywords for pages without specific keywords</p>
                                </div>
                            </div>
                        </div>

                        {{-- Analytics --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Analytics & Tracking</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="google_analytics_id" class="block text-sm font-medium text-text-secondary mb-2">Google Analytics ID</label>
                                    <input type="text" name="google_analytics_id" id="google_analytics_id" 
                                           value="{{ old('google_analytics_id', setting('seo.google_analytics_id')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="G-XXXXXXXXXX">
                                    <p class="mt-1 text-xs text-text-secondary">Your Google Analytics 4 measurement ID</p>
                                </div>

                                <div>
                                    <label for="google_search_console" class="block text-sm font-medium text-text-secondary mb-2">Google Search Console</label>
                                    <input type="text" name="google_search_console" id="google_search_console" 
                                           value="{{ old('google_search_console', setting('seo.google_search_console')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="verification meta content">
                                    <p class="mt-1 text-xs text-text-secondary">Search Console verification meta content</p>
                                </div>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Social Media</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="facebook_app_id" class="block text-sm font-medium text-text-secondary mb-2">Facebook App ID</label>
                                    <input type="text" name="facebook_app_id" id="facebook_app_id" 
                                           value="{{ old('facebook_app_id', setting('seo.facebook_app_id')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">For Facebook Open Graph tags</p>
                                </div>

                                <div>
                                    <label for="twitter_handle" class="block text-sm font-medium text-text-secondary mb-2">Twitter Handle</label>
                                    <input type="text" name="twitter_handle" id="twitter_handle" 
                                           value="{{ old('twitter_handle', setting('seo.twitter_handle')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="@yourhandle">
                                    <p class="mt-1 text-xs text-text-secondary">Your Twitter username (without @)</p>
                                </div>
                            </div>
                        </div>

                        {{-- Sitemap Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Sitemap Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="sitemap_enabled" id="sitemap_enabled" value="1" 
                                           {{ old('sitemap_enabled', setting('seo.sitemap_enabled', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="sitemap_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable XML Sitemap
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Generate and serve XML sitemap for search engines</p>

                                <div id="sitemap-frequency" class="ml-6">
                                    <label for="sitemap_frequency" class="block text-sm font-medium text-text-secondary mb-2">Update Frequency</label>
                                    <select name="sitemap_frequency" id="sitemap_frequency" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="daily" {{ old('sitemap_frequency', setting('seo.sitemap_frequency', 'weekly')) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('sitemap_frequency', setting('seo.sitemap_frequency', 'weekly')) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('sitemap_frequency', setting('seo.sitemap_frequency', 'weekly')) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">How often the sitemap is updated</p>
                                </div>
                            </div>
                        </div>

                        {{-- Robots.txt --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Robots.txt</h3>
                            <div>
                                <label for="robots_txt" class="block text-sm font-medium text-text-secondary mb-2">Custom Robots.txt Content</label>
                                <textarea name="robots_txt" id="robots_txt" rows="6" 
                                          class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent font-mono text-sm"
                                          placeholder="User-agent: *
Allow: /

Sitemap: {{ url('/sitemap.xml') }}">{{ old('robots_txt', setting('seo.robots_txt')) }}</textarea>
                                <p class="mt-1 text-xs text-text-secondary">Custom robots.txt content. Leave empty for default.</p>
                            </div>
                        </div>

                        {{-- SEO Tools --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">SEO Tools</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-text-primary mb-2">Quick Actions</h4>
                                        <div class="space-y-2">
                                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="block text-sm text-accent hover:underline">
                                                View XML Sitemap
                                            </a>
                                            <a href="{{ url('/robots.txt') }}" target="_blank" class="block text-sm text-accent hover:underline">
                                                View Robots.txt
                                            </a>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-text-primary mb-2">SEO Checklist</h4>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('seo.meta_title_template') ? 'bg-green-400' : 'bg-gray-300' }} mr-2"></div>
                                                <span class="text-text-secondary">Meta title template set</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('seo.google_analytics_id') ? 'bg-green-400' : 'bg-gray-300' }} mr-2"></div>
                                                <span class="text-text-secondary">Google Analytics configured</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 rounded-full {{ setting('seo.sitemap_enabled') ? 'bg-green-400' : 'bg-gray-300' }} mr-2"></div>
                                                <span class="text-text-secondary">XML sitemap enabled</span>
                                            </div>
                                        </div>
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
                            Save SEO Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide sitemap frequency based on checkbox
document.getElementById('sitemap_enabled').addEventListener('change', function() {
    const frequencyDiv = document.getElementById('sitemap-frequency');
    frequencyDiv.style.display = this.checked ? 'block' : 'none';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const sitemapEnabled = document.getElementById('sitemap_enabled').checked;
    document.getElementById('sitemap-frequency').style.display = sitemapEnabled ? 'block' : 'none';
});
</script>
@endsection
