@extends('layouts.admin')

@section('title', 'Integration Hub')
@section('subtitle', 'Connect your favorite tools')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Simple Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Integration Hub</h1>
                    <p class="text-gray-600 mt-2">Connect your favorite tools and services</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refreshIntegrations()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Simple Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $data['stats']['total'] }}</p>
                        <p class="text-sm text-gray-600">Available Integrations</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $data['stats']['connected'] }}</p>
                        <p class="text-sm text-gray-600">Connected</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $data['stats']['errors'] }}</p>
                        <p class="text-sm text-gray-600">Issues</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Integrations -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Popular Integrations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($data['integrations'] as $integration)
                <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-colors duration-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    {!! $integration['icon'] !!}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $integration['display_name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $integration['description'] }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                @if($integration['is_connected'])
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        Connected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                        Not Connected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            @if($integration['is_connected'])
                                <a href="{{ route('admin.integrations.show', $integration['name']) }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium text-center transition-colors duration-200">
                                    Manage
                                </a>
                                <button onclick="syncIntegration('{{ $integration['name'] }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Sync
                                </button>
                            @else
                                <a href="{{ route('admin.integrations.config', $integration['name']) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors duration-200">
                                    Connect
                                </a>
                            @endif
                        </div>

                        @if($integration['last_sync'])
                        <div class="mt-3 text-xs text-gray-500">
                            Last sync: {{ \Carbon\Carbon::parse($integration['last_sync'])->diffForHumans() }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function refreshIntegrations() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Refreshing...';
    button.disabled = true;
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function syncIntegration(integration) {
    if (confirm(`Sync data from ${integration}?`)) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Syncing...';
        button.disabled = true;
        
        fetch(`/admin/integrations/${integration}/sync`, {
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
                button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Synced!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Failed';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-red-600');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.classList.remove('bg-red-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 3000);
                
                alert('Sync failed: ' + data.message);
            }
        })
        .catch(error => {
            button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Error';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-red-600');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('bg-red-600');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 3000);
            
            alert('Sync failed: ' + error.message);
        });
    }
}
</script>
@endpush
@endsection
