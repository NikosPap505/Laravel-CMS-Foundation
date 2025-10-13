@extends('layouts.admin')

@section('title', 'Theme & Appearance')
@section('subtitle', 'Theme selection and visual customization')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Theme & Appearance</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Theme Selection --}}
            <div class="lg:col-span-2">
                <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                    <div class="p-6 md:p-8">
                        <form action="{{ route('admin.settings.store.theme') }}" method="POST">
                            @csrf
                            
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-text-primary mb-4">Select Theme</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($themes as $themeKey => $theme)
                                        <div class="theme-option {{ old('active_theme', setting('theme.active_theme', config('themes.default_theme', 'light'))) == $themeKey ? 'ring-2 ring-accent' : '' }}" 
                                             onclick="selectTheme('{{ $themeKey }}')">
                                            <input type="radio" name="active_theme" id="theme_{{ $themeKey }}" value="{{ $themeKey }}" 
                                                   {{ old('active_theme', setting('theme.active_theme', config('themes.default_theme', 'light'))) == $themeKey ? 'checked' : '' }}
                                                   class="sr-only">
                                            
                                            <div class="border border-border rounded-lg p-4 cursor-pointer hover:border-accent/50 transition-colors">
                                                <div class="flex items-center mb-3">
                                                    <div class="flex space-x-2">
                                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['colors']['primary'] }}"></div>
                                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['colors']['secondary'] }}"></div>
                                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['colors']['background'] }}"></div>
                                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $theme['colors']['surface'] }}"></div>
                                                    </div>
                                                </div>
                                                
                                                <h4 class="font-medium text-text-primary mb-1">{{ $theme['name'] }}</h4>
                                                <p class="text-sm text-text-secondary mb-2">{{ $theme['description'] }}</p>
                                                
                                                <div class="flex items-center justify-between">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent/10 text-accent">
                                                        {{ ucfirst($theme['category']) }}
                                                    </span>
                                                    <label for="theme_{{ $themeKey }}" class="text-accent text-sm font-medium cursor-pointer">
                                                        Select
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Custom Colors --}}
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-text-primary mb-4">Custom Colors</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="primary_color" class="block text-sm font-medium text-text-secondary mb-2">Primary Color</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" name="primary_color" id="primary_color" 
                                                   value="{{ old('primary_color', setting('theme.primary_color', '#3B82F6')) }}"
                                                   class="h-10 w-16 border border-border rounded cursor-pointer">
                                            <input type="text" name="primary_color_text" id="primary_color_text" 
                                                   value="{{ old('primary_color', setting('theme.primary_color', '#3B82F6')) }}"
                                                   class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                                   placeholder="#3B82F6">
                                        </div>
                                        <p class="mt-1 text-xs text-text-secondary">Override the primary theme color</p>
                                    </div>

                                    <div>
                                        <label for="secondary_color" class="block text-sm font-medium text-text-secondary mb-2">Secondary Color</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" name="secondary_color" id="secondary_color" 
                                                   value="{{ old('secondary_color', setting('theme.secondary_color', '#6B7280')) }}"
                                                   class="h-10 w-16 border border-border rounded cursor-pointer">
                                            <input type="text" name="secondary_color_text" id="secondary_color_text" 
                                                   value="{{ old('secondary_color', setting('theme.secondary_color', '#6B7280')) }}"
                                                   class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                                   placeholder="#6B7280">
                                        </div>
                                        <p class="mt-1 text-xs text-text-secondary">Override the secondary theme color</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Custom CSS --}}
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-text-primary mb-4">Custom CSS</h3>
                                <textarea name="custom_css" id="custom_css" rows="8" 
                                          class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent font-mono text-sm"
                                          placeholder="/* Add your custom CSS here */
.custom-class {
    color: #your-color;
}">{{ old('custom_css', setting('theme.custom_css')) }}</textarea>
                                <p class="mt-1 text-xs text-text-secondary">Add custom CSS to override theme styles</p>
                            </div>

                            {{-- Custom JavaScript --}}
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-text-primary mb-4">Custom JavaScript</h3>
                                <textarea name="custom_javascript" id="custom_javascript" rows="6" 
                                          class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent font-mono text-sm"
                                          placeholder="// Add your custom JavaScript here
document.addEventListener('DOMContentLoaded', function() {
    // Your custom code
});">{{ old('custom_javascript', setting('theme.custom_javascript')) }}</textarea>
                                <p class="mt-1 text-xs text-text-secondary">Add custom JavaScript for enhanced functionality</p>
                            </div>

                            <div class="flex items-center justify-end border-t border-border pt-6">
                                <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" class="btn-primary">
                                    Save Theme Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Theme Preview --}}
            <div class="lg:col-span-1">
                <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-text-primary mb-4">Theme Preview</h3>
                        
                        <div id="theme-preview" class="space-y-4">
                            {{-- Preview content that updates based on selected theme --}}
                            <div class="preview-card bg-background border border-border rounded-lg p-4">
                                <h4 class="font-semibold text-text-primary mb-2">Sample Card</h4>
                                <p class="text-text-secondary text-sm mb-3">This is how your content will look with the selected theme.</p>
                                <button class="btn-primary text-sm px-3 py-1">Sample Button</button>
                            </div>
                            
                            <div class="preview-navigation bg-surface border border-border rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-text-primary font-medium">Navigation</span>
                                    <span class="text-accent text-sm">Active</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-accent/10 rounded-lg">
                            <h4 class="font-medium text-text-primary mb-2">Theme Information</h4>
                            <div id="theme-info">
                                <p class="text-sm text-text-secondary">Select a theme to see details</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectTheme(themeKey) {
    // Update radio button
    document.getElementById('theme_' + themeKey).checked = true;
    
    // Update visual selection
    document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-accent');
    });
    event.currentTarget.classList.add('ring-2', 'ring-accent');
    
    // Update preview (this would need actual theme data from backend)
    updateThemePreview(themeKey);
}

function updateThemePreview(themeKey) {
    // This would ideally fetch theme data and update the preview
    // For now, we'll just show a message
    const themeInfo = document.getElementById('theme-info');
    themeInfo.innerHTML = `<p class="text-sm text-text-secondary">Theme: ${themeKey}</p>`;
}

// Sync color picker with text input
document.getElementById('primary_color').addEventListener('input', function() {
    document.getElementById('primary_color_text').value = this.value;
});

document.getElementById('primary_color_text').addEventListener('input', function() {
    document.getElementById('primary_color').value = this.value;
});

document.getElementById('secondary_color').addEventListener('input', function() {
    document.getElementById('secondary_color_text').value = this.value;
});

document.getElementById('secondary_color_text').addEventListener('input', function() {
    document.getElementById('secondary_color').value = this.value;
});
</script>
@endsection
