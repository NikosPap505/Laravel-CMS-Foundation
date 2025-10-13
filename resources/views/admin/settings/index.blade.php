@extends('layouts.admin')

@section('title', 'Settings')
@section('subtitle', 'Configure your CMS settings')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-text-primary mb-2">Settings</h1>
            <p class="text-text-secondary">Configure your CMS settings across all categories</p>
        </div>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($settingsCategories as $key => $category)
                <a href="{{ route($category['route']) }}" class="group">
                    <div class="bg-surface border border-border rounded-lg p-6 hover:shadow-lg transition-all duration-200 hover:border-accent/50">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                @switch($category['icon'])
                                    @case('cog')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        @break
                                    @case('search')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        @break
                                    @case('mail')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                    @case('footer')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        @break
                                    @case('palette')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                        </svg>
                                        @break
                                    @case('document-text')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        @break
                                    @case('sparkles')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        @break
                                    @case('link')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        @break
                                    @case('shield-check')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        @break
                                    @case('archive-box')
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="h-8 w-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-text-primary group-hover:text-accent transition-colors">
                                    {{ $category['title'] }}
                                </h3>
                            </div>
                        </div>
                        <p class="text-sm text-text-secondary">
                            {{ $category['description'] }}
                        </p>
                        <div class="mt-4 flex items-center text-accent text-sm font-medium group-hover:text-accent/80 transition-colors">
                            Configure
                            <svg class="ml-1 h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Quick Actions Section --}}
        <div class="mt-12 bg-surface border border-border rounded-lg p-6">
            <h2 class="text-xl font-semibold text-text-primary mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Clear All Cache
                    </button>
                </form>
                <form action="{{ route('admin.settings.optimize-database') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Optimize Database
                    </button>
                </form>
                <a href="{{ route('admin.integrations.index') }}" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center inline-block">
                    View Integrations
                </a>
            </div>
        </div>

        {{-- System Status --}}
        <div class="mt-8 bg-surface border border-border rounded-lg p-6">
            <h2 class="text-xl font-semibold text-text-primary mb-4">System Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-3 w-3 bg-green-400 rounded-full"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-text-primary">Cache</p>
                        <p class="text-xs text-text-secondary">Ready</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-3 w-3 bg-green-400 rounded-full"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-text-primary">Database</p>
                        <p class="text-xs text-text-secondary">Connected</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-3 w-3 bg-green-400 rounded-full"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-text-primary">Storage</p>
                        <p class="text-xs text-text-secondary">Available</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-3 w-3 bg-yellow-400 rounded-full"></div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-text-primary">AI Services</p>
                        <p class="text-xs text-text-secondary">Configure Required</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection