@extends('layouts.admin')

@section('title', 'General Settings')
@section('subtitle', 'Basic site configuration and information')

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">General Settings</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 md:p-8">
                @if (session('success'))
                    <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.settings.store.general') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-8">
                        {{-- Site Information --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Site Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="site_name" class="block text-sm font-medium text-text-secondary mb-2">Site Name</label>
                                    <input type="text" name="site_name" id="site_name" 
                                           value="{{ old('site_name', setting('general.site_name', config('app.name'))) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">The name of your website</p>
                                </div>

                                <div>
                                    <label for="site_tagline" class="block text-sm font-medium text-text-secondary mb-2">Site Tagline</label>
                                    <input type="text" name="site_tagline" id="site_tagline" 
                                           value="{{ old('site_tagline', setting('general.site_tagline')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">A short description of your site</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="site_description" class="block text-sm font-medium text-text-secondary mb-2">Site Description</label>
                                    <textarea name="site_description" id="site_description" rows="3" 
                                              class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('site_description', setting('general.site_description')) }}</textarea>
                                    <p class="mt-1 text-xs text-text-secondary">A longer description of your website for SEO purposes</p>
                                </div>
                            </div>
                        </div>

                        {{-- Branding --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Branding</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="logo" class="block text-sm font-medium text-text-secondary mb-2">Logo</label>
                                    <input type="file" name="logo" id="logo" accept="image/*" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    @if(setting('general.logo'))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(setting('general.logo')) }}" alt="Current Logo" class="h-16 w-auto">
                                            <p class="text-xs text-text-secondary mt-1">Current logo</p>
                                        </div>
                                    @endif
                                    <p class="mt-1 text-xs text-text-secondary">Upload your site logo (PNG, JPG, GIF, SVG)</p>
                                </div>

                                <div>
                                    <label for="favicon" class="block text-sm font-medium text-text-secondary mb-2">Favicon</label>
                                    <input type="file" name="favicon" id="favicon" accept="image/*" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    @if(setting('general.favicon'))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(setting('general.favicon')) }}" alt="Current Favicon" class="h-8 w-8">
                                            <p class="text-xs text-text-secondary mt-1">Current favicon</p>
                                        </div>
                                    @endif
                                    <p class="mt-1 text-xs text-text-secondary">Upload your site favicon (ICO, PNG, JPG)</p>
                                </div>
                            </div>
                        </div>

                        {{-- Localization --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Localization</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-text-secondary mb-2">Timezone</label>
                                    <select name="timezone" id="timezone" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="">Select Timezone</option>
                                        @foreach(timezone_identifiers_list() as $tz)
                                            <option value="{{ $tz }}" {{ old('timezone', setting('general.timezone', config('app.timezone'))) == $tz ? 'selected' : '' }}>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Your site's timezone</p>
                                </div>

                                <div>
                                    <label for="language" class="block text-sm font-medium text-text-secondary mb-2">Language</label>
                                    <select name="language" id="language" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="en" {{ old('language', setting('general.language', 'en')) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ old('language', setting('general.language', 'en')) == 'es' ? 'selected' : '' }}>Spanish</option>
                                        <option value="fr" {{ old('language', setting('general.language', 'en')) == 'fr' ? 'selected' : '' }}>French</option>
                                        <option value="de" {{ old('language', setting('general.language', 'en')) == 'de' ? 'selected' : '' }}>German</option>
                                        <option value="it" {{ old('language', setting('general.language', 'en')) == 'it' ? 'selected' : '' }}>Italian</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Default site language</p>
                                </div>

                                <div>
                                    <label for="date_format" class="block text-sm font-medium text-text-secondary mb-2">Date Format</label>
                                    <select name="date_format" id="date_format" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="Y-m-d" {{ old('date_format', setting('general.date_format', 'Y-m-d')) == 'Y-m-d' ? 'selected' : '' }}>2024-01-15</option>
                                        <option value="m/d/Y" {{ old('date_format', setting('general.date_format', 'Y-m-d')) == 'm/d/Y' ? 'selected' : '' }}>01/15/2024</option>
                                        <option value="d/m/Y" {{ old('date_format', setting('general.date_format', 'Y-m-d')) == 'd/m/Y' ? 'selected' : '' }}>15/01/2024</option>
                                        <option value="F j, Y" {{ old('date_format', setting('general.date_format', 'Y-m-d')) == 'F j, Y' ? 'selected' : '' }}>January 15, 2024</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">How dates are displayed</p>
                                </div>
                            </div>
                        </div>

                        {{-- System Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">System Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" 
                                           {{ old('maintenance_mode', setting('general.maintenance_mode')) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="maintenance_mode" class="ml-2 block text-sm text-text-primary">
                                        Enable Maintenance Mode
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">When enabled, only administrators can access the site</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-border pt-6">
                        <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Save General Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
