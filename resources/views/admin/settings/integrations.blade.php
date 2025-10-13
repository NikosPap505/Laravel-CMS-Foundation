@extends('layouts.admin')

@section('title', 'Integration Settings')
@section('subtitle', 'Third-party service integrations')

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Integration Settings</span>
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
                <form action="{{ route('admin.settings.store.integrations') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- Google Services --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Google Services</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="google_maps_api_key" class="block text-sm font-medium text-text-secondary mb-2">Google Maps API Key</label>
                                    <input type="text" name="google_maps_api_key" id="google_maps_api_key" 
                                           value="{{ old('google_maps_api_key', setting('integrations.google_maps_api_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="AIzaSyB...">
                                    <p class="mt-1 text-xs text-text-secondary">For Google Maps integration</p>
                                </div>

                                <div>
                                    <label for="recaptcha_site_key" class="block text-sm font-medium text-text-secondary mb-2">reCAPTCHA Site Key</label>
                                    <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" 
                                           value="{{ old('recaptcha_site_key', setting('integrations.recaptcha_site_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="6Le...">
                                    <p class="mt-1 text-xs text-text-secondary">Public key for reCAPTCHA</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="recaptcha_secret_key" class="block text-sm font-medium text-text-secondary mb-2">reCAPTCHA Secret Key</label>
                                    <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key" 
                                           value="{{ old('recaptcha_secret_key', setting('integrations.recaptcha_secret_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="6Le...">
                                    <p class="mt-1 text-xs text-text-secondary">Secret key for reCAPTCHA verification</p>
                                </div>
                            </div>
                        </div>

                        {{-- Email Marketing --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Email Marketing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mailchimp_api_key" class="block text-sm font-medium text-text-secondary mb-2">Mailchimp API Key</label>
                                    <input type="password" name="mailchimp_api_key" id="mailchimp_api_key" 
                                           value="{{ old('mailchimp_api_key', setting('integrations.mailchimp_api_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="Your Mailchimp API key">
                                    <p class="mt-1 text-xs text-text-secondary">For newsletter integration</p>
                                </div>

                                <div>
                                    <label for="mailchimp_list_id" class="block text-sm font-medium text-text-secondary mb-2">Mailchimp List ID</label>
                                    <input type="text" name="mailchimp_list_id" id="mailchimp_list_id" 
                                           value="{{ old('mailchimp_list_id', setting('integrations.mailchimp_list_id')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="abc123def4">
                                    <p class="mt-1 text-xs text-text-secondary">Your Mailchimp audience/list ID</p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Processing --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Payment Processing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="stripe_public_key" class="block text-sm font-medium text-text-secondary mb-2">Stripe Public Key</label>
                                    <input type="text" name="stripe_public_key" id="stripe_public_key" 
                                           value="{{ old('stripe_public_key', setting('integrations.stripe_public_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="pk_test_...">
                                    <p class="mt-1 text-xs text-text-secondary">Publishable key for Stripe payments</p>
                                </div>

                                <div>
                                    <label for="stripe_secret_key" class="block text-sm font-medium text-text-secondary mb-2">Stripe Secret Key</label>
                                    <input type="password" name="stripe_secret_key" id="stripe_secret_key" 
                                           value="{{ old('stripe_secret_key', setting('integrations.stripe_secret_key')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="sk_test_...">
                                    <p class="mt-1 text-xs text-text-secondary">Secret key for Stripe payments</p>
                                </div>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Social Media</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Facebook Integration</h4>
                                            <p class="text-sm text-text-secondary">Connect with Facebook for social sharing</p>
                                        </div>
                                        <button type="button" class="text-sm text-gray-400 cursor-not-allowed" 
                                                title="Coming soon" disabled>
                                            Configure
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Twitter Integration</h4>
                                            <p class="text-sm text-text-secondary">Connect with Twitter for social sharing</p>
                                        </div>
                                        <button type="button" class="text-sm text-gray-400 cursor-not-allowed" 
                                                title="Coming soon" disabled>
                                            Configure
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">LinkedIn Integration</h4>
                                            <p class="text-sm text-text-secondary">Connect with LinkedIn for professional sharing</p>
                                        </div>
                                        <button type="button" class="text-sm text-gray-400 cursor-not-allowed" 
                                                title="Coming soon" disabled>
                                            Configure
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Analytics & Tracking --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Analytics & Tracking</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Google Analytics</h4>
                                            <p class="text-sm text-text-secondary">Track website traffic and user behavior</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-green-600">Connected</span>
                                            <button type="button" class="text-sm text-accent hover:underline">
                                                Configure
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Facebook Pixel</h4>
                                            <p class="text-sm text-text-secondary">Track conversions and optimize ads</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Not Connected</span>
                                            <button type="button" class="text-sm text-accent hover:underline">
                                                Connect
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-text-primary">Hotjar</h4>
                                            <p class="text-sm text-text-secondary">User behavior analytics and heatmaps</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Not Connected</span>
                                            <button type="button" class="text-sm text-accent hover:underline">
                                                Connect
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Integration Status --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Integration Status</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">Google Maps</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">{{ setting('integrations.google_maps_api_key') ? 'Configured' : 'Not configured' }}</p>
                                </div>

                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 {{ setting('integrations.recaptcha_site_key') ? 'bg-green-400' : 'bg-gray-300' }} rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">reCAPTCHA</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">{{ setting('integrations.recaptcha_site_key') ? 'Configured' : 'Not configured' }}</p>
                                </div>

                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 {{ setting('integrations.mailchimp_api_key') ? 'bg-green-400' : 'bg-gray-300' }} rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">Mailchimp</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">{{ setting('integrations.mailchimp_api_key') ? 'Connected' : 'Not connected' }}</p>
                                </div>

                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 {{ setting('integrations.stripe_public_key') ? 'bg-green-400' : 'bg-gray-300' }} rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">Stripe</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">{{ setting('integrations.stripe_public_key') ? 'Configured' : 'Not configured' }}</p>
                                </div>

                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">Facebook</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">Not connected</p>
                                </div>

                                <div class="bg-background border border-border rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                        <span class="font-medium text-text-primary">Twitter</span>
                                    </div>
                                    <p class="text-sm text-text-secondary">Not connected</p>
                                </div>
                            </div>
                        </div>

                        {{-- Integration Hub Link --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Advanced Integrations</h3>
                            <div class="bg-accent/10 border border-accent/20 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-text-primary">Integration Hub</h4>
                                        <p class="text-sm text-text-secondary">Access advanced integrations and API connections</p>
                                    </div>
                                    <a href="{{ route('admin.integrations.index') }}" class="bg-accent hover:bg-accent/90 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Open Hub
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-border pt-6">
                        <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Save Integration Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
