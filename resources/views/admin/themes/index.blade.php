@extends('layouts.admin')

@section('title', 'Theme Management')
@section('subtitle', 'Customize your CMS appearance')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Theme Management</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Choose from our collection of beautiful themes to personalize your CMS experience.</p>
        </div>

        <!-- Current Theme Display -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Current Theme</h2>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-lg border-2 border-gray-200 dark:border-gray-600 flex items-center justify-center"
                         style="background: linear-gradient(135deg, {{ config('themes.available_themes.' . (auth()->user()->theme_preference ?? 'light') . '.colors.primary') }}, {{ config('themes.available_themes.' . (auth()->user()->theme_preference ?? 'light') . '.colors.secondary') }})">
                        <span class="text-white font-bold text-lg">{{ substr(config('themes.available_themes.' . (auth()->user()->theme_preference ?? 'light') . '.name'), 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ config('themes.available_themes.' . (auth()->user()->theme_preference ?? 'light') . '.name') }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ config('themes.available_themes.' . (auth()->user()->theme_preference ?? 'light') . '.description') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme Categories -->
        @foreach(config('themes.theme_categories') as $categoryKey => $category)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex items-center space-x-2">
                    @if($category['icon'] === 'briefcase')
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                    @elseif($category['icon'] === 'palette')
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                        </svg>
                    @elseif($category['icon'] === 'sparkles')
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    @elseif($category['icon'] === 'eye')
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    @endif
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $category['name'] }}</h2>
                </div>
                <p class="text-gray-600 dark:text-gray-400 ml-4">{{ $category['description'] }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(config('themes.available_themes') as $themeKey => $theme)
                    @if($theme['category'] === $categoryKey)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <!-- Theme Preview -->
                        <div class="h-32 relative" 
                             style="background: linear-gradient(135deg, {{ $theme['colors']['primary'] }}, {{ $theme['colors']['secondary'] }})">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="bg-white bg-opacity-90 rounded-lg p-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Theme Info -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $theme['name'] }}</h3>
                                @if((auth()->user()->theme_preference ?? 'light') === $themeKey)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $theme['description'] }}</p>

                            <!-- Color Palette -->
                            <div class="flex space-x-2 mb-4">
                                <div class="w-6 h-6 rounded-full border border-gray-200 dark:border-gray-600" 
                                     style="background-color: {{ $theme['colors']['primary'] }}"></div>
                                <div class="w-6 h-6 rounded-full border border-gray-200 dark:border-gray-600" 
                                     style="background-color: {{ $theme['colors']['secondary'] }}"></div>
                                <div class="w-6 h-6 rounded-full border border-gray-200 dark:border-gray-600" 
                                     style="background-color: {{ $theme['colors']['background'] }}"></div>
                                <div class="w-6 h-6 rounded-full border border-gray-200 dark:border-gray-600" 
                                     style="background-color: {{ $theme['colors']['surface'] }}"></div>
                            </div>

                            <!-- Apply Button -->
                            <button onclick="applyTheme('{{ $themeKey }}')" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 {{ (auth()->user()->theme_preference ?? 'light') === $themeKey ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ (auth()->user()->theme_preference ?? 'light') === $themeKey ? 'disabled' : '' }}>
                                {{ (auth()->user()->theme_preference ?? 'light') === $themeKey ? 'Currently Active' : 'Apply Theme' }}
                            </button>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
async function applyTheme(themeKey) {
    try {
        const response = await fetch('{{ route("admin.theme.switch") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ theme: themeKey })
        });

        if (response.ok) {
            // Apply theme immediately
            document.documentElement.setAttribute('data-theme', themeKey);
            document.body.setAttribute('data-theme', themeKey);
            
            // Show success message
            showNotification('Theme applied successfully!', 'success');
            
            // Reload page after a short delay to update the UI
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('Failed to apply theme. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Failed to apply theme:', error);
        showNotification('Failed to apply theme. Please try again.', 'error');
    }
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
