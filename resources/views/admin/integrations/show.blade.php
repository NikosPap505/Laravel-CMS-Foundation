@extends('layouts.admin')

@section('title', 'Integration Details')
@section('subtitle', 'View and manage integration settings')

@section('content')

<div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <a href="{{ route('admin.integrations.index') }}" class="mr-4 text-text-secondary hover:text-text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-text-primary flex items-center">
                        <div class="text-3xl mr-3">{{ $integration->getIcon() }}</div>
                        {{ $integration->getDisplayName() }}
                    </h1>
                    <p class="text-text-secondary mt-2">{{ $integration->getDescription() }}</p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="syncIntegration()" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sync Data
                </button>
                <button onclick="disconnectIntegration()" class="btn-danger">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Disconnect
                </button>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-surface border border-border rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-text-secondary">Status</p>
                        <p class="text-2xl font-semibold text-text-primary">
                            @if($dbIntegration && $dbIntegration->is_connected)
                                <span class="text-green-600">Connected</span>
                            @else
                                <span class="text-red-600">Disconnected</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-border rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-text-secondary">Health</p>
                        <p class="text-2xl font-semibold text-text-primary">
                            @if(isset($healthMetrics['status']))
                                @if($healthMetrics['status'] === 'healthy')
                                    <span class="text-green-600">Healthy</span>
                                @elseif($healthMetrics['status'] === 'warning')
                                    <span class="text-yellow-600">Warning</span>
                                @else
                                    <span class="text-red-600">Error</span>
                                @endif
                            @else
                                <span class="text-gray-600">Unknown</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-border rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-text-secondary">Last Sync</p>
                        <p class="text-2xl font-semibold text-text-primary">
                            @if($dbIntegration && $dbIntegration->last_sync)
                                {{ \Carbon\Carbon::parse($dbIntegration->last_sync)->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-surface border border-border rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-text-secondary">Data Points</p>
                        <p class="text-2xl font-semibold text-text-primary">
                            {{ $healthMetrics['data_points'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Integration Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Health Metrics -->
                <div class="bg-surface border border-border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Health Metrics</h3>
                    <div class="space-y-4">
                        @if(isset($healthMetrics['response_time']))
                        <div class="flex justify-between items-center">
                            <span class="text-text-secondary">Response Time</span>
                            <span class="font-medium text-text-primary">{{ $healthMetrics['response_time'] }}ms</span>
                        </div>
                        @endif
                        
                        @if(isset($healthMetrics['uptime']))
                        <div class="flex justify-between items-center">
                            <span class="text-text-secondary">Uptime</span>
                            <span class="font-medium text-text-primary">{{ $healthMetrics['uptime'] }}%</span>
                        </div>
                        @endif
                        
                        @if(isset($healthMetrics['error_rate']))
                        <div class="flex justify-between items-center">
                            <span class="text-text-secondary">Error Rate</span>
                            <span class="font-medium {{ $healthMetrics['error_rate'] > 5 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $healthMetrics['error_rate'] }}%
                            </span>
                        </div>
                        @endif
                        
                        @if(isset($healthMetrics['last_error']))
                        <div class="flex justify-between items-center">
                            <span class="text-text-secondary">Last Error</span>
                            <span class="font-medium text-text-primary">{{ $healthMetrics['last_error'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-surface border border-border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @if($dbIntegration && $dbIntegration->last_sync)
                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-800">Data synchronized successfully</p>
                                <p class="text-xs text-green-600">{{ \Carbon\Carbon::parse($dbIntegration->last_sync)->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($dbIntegration && $dbIntegration->created_at)
                        <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Integration connected</p>
                                <p class="text-xs text-blue-600">{{ \Carbon\Carbon::parse($dbIntegration->created_at)->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-surface border border-border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button onclick="testConnection()" class="w-full btn-secondary text-left">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Test Connection
                        </button>
                        
                        <button onclick="viewLogs()" class="w-full btn-secondary text-left">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            View Logs
                        </button>
                        
                        <a href="{{ route('admin.integrations.config', $integration->getName()) }}" class="w-full btn-secondary text-left">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Configure
                        </a>
                    </div>
                </div>

                <!-- Integration Info -->
                <div class="bg-surface border border-border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Integration Info</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Name:</span>
                            <span class="text-text-primary">{{ $integration->getName() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Version:</span>
                            <span class="text-text-primary">{{ $integration->getVersion() ?? '1.0.0' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Category:</span>
                            <span class="text-text-primary">{{ $integration->getCategory() ?? 'General' }}</span>
                        </div>
                        @if($integration->getDocumentationUrl())
                        <div class="pt-2">
                            <a href="{{ $integration->getDocumentationUrl() }}" target="_blank" class="text-accent hover:text-accent/80 text-sm">
                                View Documentation →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-surface border border-border rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Features</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($integration->getFeatures() as $feature)
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function syncIntegration() {
    if (confirm('Sync data from {{ $integration->getDisplayName() }}?')) {
        fetch(`/admin/integrations/{{ $integration->getName() }}/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sync completed successfully!');
                location.reload();
            } else {
                alert('Sync failed: ' + data.message);
            }
        })
        .catch(error => {
            alert('Sync failed: ' + error.message);
        });
    }
}

function disconnectIntegration() {
    if (confirm('Are you sure you want to disconnect from {{ $integration->getDisplayName() }}? This will stop all data synchronization.')) {
        fetch(`/admin/integrations/{{ $integration->getName() }}/disconnect`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Integration disconnected successfully!');
                window.location.href = '/admin/integrations';
            } else {
                alert('Disconnect failed: ' + data.message);
            }
        })
        .catch(error => {
            alert('Disconnect failed: ' + error.message);
        });
    }
}

function testConnection() {
    fetch(`/admin/integrations/{{ $integration->getName() }}/test`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Connection test successful! ✅');
        } else {
            alert('Connection test failed: ' + data.message);
        }
    })
    .catch(error => {
        alert('Connection test failed: ' + error.message);
    });
}

function viewLogs() {
    // This would typically open a modal or navigate to a logs page
    alert('Logs feature coming soon!');
}
</script>
@endpush
@endsection
