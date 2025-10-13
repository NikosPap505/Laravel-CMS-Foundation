@extends('layouts.admin')

@section('title', 'Backup & Cache')
@section('subtitle', 'System maintenance and cache management')

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Backup & Cache</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-medium text-text-primary mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear All Cache
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.settings.optimize-database') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                Optimize Database
                            </button>
                        </form>

                        <button onclick="createBackup()" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-3 rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Create Backup
                        </button>
                    </div>
                </div>
            </div>

            {{-- Backup Settings --}}
            <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                <div class="p-6 md:p-8">
                    <form action="{{ route('admin.settings.store.backup') }}" method="POST">
                        @csrf
                        <div class="space-y-8">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Backup Settings</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" name="backup_enabled" id="backup_enabled" value="1" 
                                           {{ old('backup_enabled', setting('backup.backup_enabled', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="backup_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable Automatic Backups
                                    </label>
                                </div>

                                <div>
                                    <label for="backup_frequency" class="block text-sm font-medium text-text-secondary mb-2">Backup Frequency</label>
                                    <select name="backup_frequency" id="backup_frequency" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="daily" {{ old('backup_frequency', setting('backup.backup_frequency', 'daily')) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('backup_frequency', setting('backup.backup_frequency', 'daily')) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ old('backup_frequency', setting('backup.backup_frequency', 'daily')) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="backup_retention_days" class="block text-sm font-medium text-text-secondary mb-2">Retention Period (Days)</label>
                                    <input type="number" name="backup_retention_days" id="backup_retention_days" 
                                           value="{{ old('backup_retention_days', setting('backup.backup_retention_days', 30)) }}" 
                                           min="1" max="365"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">How long to keep backup files</p>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="auto_optimize_database" id="auto_optimize_database" value="1" 
                                           {{ old('auto_optimize_database', setting('backup.auto_optimize_database', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="auto_optimize_database" class="ml-2 block text-sm text-text-primary">
                                        Auto-optimize Database
                                    </label>
                                </div>
                            </div>

                            <div class="flex items-center justify-end border-t border-border pt-6">
                                <button type="submit" class="btn-primary">
                                    Save Backup Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Cache Settings --}}
            <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                <div class="p-6 md:p-8">
                    <form action="{{ route('admin.settings.store.backup') }}" method="POST">
                        @csrf
                        <div class="space-y-8">
                            <h3 class="text-lg font-medium text-text-primary mb-4">Cache Settings</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="cache_driver" class="block text-sm font-medium text-text-secondary mb-2">Cache Driver</label>
                                    <select name="cache_driver" id="cache_driver" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="file" {{ old('cache_driver', setting('backup.cache_driver', 'file')) == 'file' ? 'selected' : '' }}>File</option>
                                        <option value="database" {{ old('cache_driver', setting('backup.cache_driver', 'file')) == 'database' ? 'selected' : '' }}>Database</option>
                                        <option value="redis" {{ old('cache_driver', setting('backup.cache_driver', 'file')) == 'redis' ? 'selected' : '' }}>Redis</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Storage method for cached data</p>
                                </div>

                                <div>
                                    <label for="cache_ttl" class="block text-sm font-medium text-text-secondary mb-2">Cache TTL (seconds)</label>
                                    <input type="number" name="cache_ttl" id="cache_ttl" 
                                           value="{{ old('cache_ttl', setting('backup.cache_ttl', 3600)) }}" 
                                           min="60" max="86400"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Time to live for cached content (1 hour = 3600 seconds)</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end border-t border-border pt-6">
                                <button type="submit" class="btn-primary">
                                    Save Cache Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- System Information --}}
            <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-medium text-text-primary mb-4">System Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-background border border-border rounded-lg p-4">
                            <h4 class="font-medium text-text-primary mb-2">Cache Status</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Driver:</span>
                                    <span class="text-text-primary">{{ config('cache.default', 'file') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Status:</span>
                                    <span class="text-green-600 font-medium">Active</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-background border border-border rounded-lg p-4">
                            <h4 class="font-medium text-text-primary mb-2">Database</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Driver:</span>
                                    <span class="text-text-primary">{{ config('database.default', 'sqlite') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Status:</span>
                                    <span class="text-green-600 font-medium">Connected</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-background border border-border rounded-lg p-4">
                            <h4 class="font-medium text-text-primary mb-2">Storage</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Disk:</span>
                                    <span class="text-text-primary">{{ config('filesystems.default', 'local') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Status:</span>
                                    <span class="text-green-600 font-medium">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Backups --}}
            <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-medium text-text-primary mb-4">Recent Backups</h3>
                    
                    <div class="bg-background border border-border rounded-lg p-4">
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-text-secondary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-text-secondary">No backups found</p>
                            <p class="text-sm text-text-secondary mt-1">Create your first backup to see it listed here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function createBackup() {
    if (confirm('Are you sure you want to create a backup? This may take a few minutes.')) {
        // This would typically make an AJAX call to create a backup
        alert('Backup creation initiated. You will be notified when it\'s complete.');
    }
}
</script>
@endsection
