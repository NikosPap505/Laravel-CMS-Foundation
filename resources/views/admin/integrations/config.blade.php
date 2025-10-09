@extends('layouts.admin')

@section('title', 'Connect ' . $integration->getDisplayName())
@section('subtitle', $integration->getDescription())

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Simple Header -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    {!! $integration->getIcon() !!}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Connect {{ $integration->getDisplayName() }}</h1>
                    <p class="text-gray-600">{{ $integration->getDescription() }}</p>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form id="integrationForm" method="POST" action="{{ route('admin.integrations.connect', $integration->getName()) }}">
                @csrf
                
                <div class="space-y-4">
                    @foreach($requiredFields as $field => $config)
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">
                            {{ $config['label'] ?? ucwords(str_replace(['_', '-'], ' ', $field)) }}
                            @if($config['required'] ?? true)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        
                        @if($config['description'] ?? false)
                            <p class="text-sm text-gray-600 mb-2">{{ $config['description'] }}</p>
                        @endif
                        
                        @if(($config['type'] ?? 'text') === 'textarea')
                            <textarea 
                                name="config[{{ $field }}]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                rows="{{ $config['rows'] ?? 4 }}"
                                placeholder="{{ $config['placeholder'] ?? '' }}"
                                @if($config['required'] ?? true) required @endif
                            >{{ old("config.{$field}") }}</textarea>
                        @else
                            <input 
                                type="{{ $config['type'] ?? 'text' }}" 
                                name="config[{{ $field }}]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="{{ $config['placeholder'] ?? '' }}"
                                value="{{ old("config.{$field}") }}"
                                @if($config['required'] ?? true) required @endif
                            />
                        @endif
                        
                        @error("config.{$field}")
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="testConnection()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Test Connection
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        Connect
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
function testConnection() {
    const form = document.getElementById('integrationForm');
    const formData = new FormData(form);
    const config = {};
    
    // Extract config data
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('config[')) {
            const field = key.replace('config[', '').replace(']', '');
            config[field] = value;
        }
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = 'Testing...';
    button.disabled = true;
    
    fetch(`/admin/integrations/{{ $integration->getName() }}/test`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ config })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = 'Success!';
            button.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            button.classList.add('bg-green-600', 'text-white');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('bg-green-600', 'text-white');
                button.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            }, 3000);
        } else {
            button.innerHTML = 'Failed';
            button.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            button.classList.add('bg-red-600', 'text-white');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('bg-red-600', 'text-white');
                button.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            }, 3000);
            
            alert('Connection test failed: ' + data.message);
        }
    })
    .catch(error => {
        button.innerHTML = 'Error';
        button.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
        button.classList.add('bg-red-600', 'text-white');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('bg-red-600', 'text-white');
            button.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
        }, 3000);
        
        alert('Connection test failed: ' + error.message);
    });
}
</script>
@endpush
@endsection