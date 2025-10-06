<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

        <!-- TinyMCE -->
        <script src="https://cdn.tiny.cloud/1/f8n0l9nzwigjdcwl2jmcyxwlevc051jlfzuea95mm8wgk35v/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    </head>
    <body class="font-sans antialiased bg-background text-text-secondary">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-800 dark:text-gray-200">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        {{-- Flash Messages --}}
        <x-flash-messages />

        {{-- Keyboard Shortcut Hint --}}
        <div class="fixed bottom-4 right-4 z-40">
            <button onclick="window.shortcuts?.showHelp()" 
                    class="bg-accent text-white px-3 py-2 rounded-lg shadow-lg hover:bg-opacity-90 transition text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Shortcuts
            </button>
        </div>

        @stack('scripts')

    <script>
        // Check if both title and slug fields exist on the page
        if (document.getElementById('title') && document.getElementById('slug')) {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            titleInput.addEventListener('keyup', function() {
                // Convert the title to a URL-friendly slug
                const slug = this.value.toString().toLowerCase()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^¿-\u1FFF\u2C00-\uD7FF\w\-]+/g, '') // Remove all non-word chars except -
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text

                slugInput.value = slug;
            });
        }
    </script>
    </body>
</html>
