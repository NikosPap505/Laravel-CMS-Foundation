<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ auth()->user()->theme_preference ?? 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Make current route available to Alpine.js
        window.currentRoute = '{{ request()->route()->getName() }}';
    </script>

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/themes.css', 'resources/js/app.js'])
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NWWKG88R');</script>
    <!-- End Google Tag Manager -->
</head>
<body class="font-sans antialiased transition-colors duration-300" data-theme="{{ auth()->user()->theme_preference ?? 'light' }}">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NWWKG88R"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div x-data="adminLayout" class="flex h-screen transition-colors duration-300" 
         :class="theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50'">
        
        <!-- Left Sidebar Navigation -->
        <div class="w-64 border-r flex flex-col transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            
            <!-- Logo/Brand -->
            <div class="p-4 border-b transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <h1 class="text-xl font-bold transition-colors duration-300"
                    :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">CMS Admin</h1>
                <p class="text-sm transition-colors duration-300"
                   :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Content Management</p>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-300"
                   :class="currentRoute === 'dashboard' ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Dashboard
                </a>
                
                <!-- Content Management -->
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Content</div>
                    
                    <a href="{{ route('admin.posts.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.posts') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-100 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Posts
                    </a>
                    
                    <a href="{{ route('admin.pages.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.pages') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Pages
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.categories') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Categories
                    </a>
                    
                    <a href="{{ route('admin.media.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.media') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Media
                    </a>
                    
                    <a href="{{ route('admin.menu-items.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.menu-items') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Menu Items
                    </a>
                    
                    <a href="{{ route('admin.footer.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.footer') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        Footer Content
                    </a>
                </div>
                
                <!-- Tools & Features -->
                <div class="space-y-1 mt-6">
                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Tools</div>
                    
                    <a href="{{ route('admin.ai.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.ai') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        AI Assistant
                    </a>
                    
                    <a href="{{ route('admin.integrations.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.integrations') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Integrations
                    </a>
                    
                    <a href="{{ route('admin.comments.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.comments') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Comments
                    </a>
                </div>
                
                <!-- System -->
                <div class="space-y-1 mt-6">
                    <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">System</div>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.users') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users
                    </a>
                    
                    <a href="{{ route('admin.themes.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.themes') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                        </svg>
                        Themes
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-300"
                       :class="currentRoute.startsWith('admin.settings') ? 'text-white bg-blue-600' : (theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100')">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>
            
            <!-- User Info -->
            <div class="p-4 border-t transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                             :class="theme === 'dark' ? 'bg-gray-700' : 'bg-gray-300'">
                            <span class="text-sm font-medium transition-colors duration-300"
                                  :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium transition-colors duration-300"
                                 :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">{{ auth()->user()->name }}</div>
                            <div class="text-xs transition-colors duration-300"
                                 :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="px-6 py-4 border-b transition-colors duration-300"
                    :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold transition-colors duration-300"
                            :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">@yield('title', 'Admin Panel')</h1>
                        <p class="text-sm transition-colors duration-300"
                           :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">@yield('subtitle', 'Content Management System')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Theme Switcher -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors duration-200"
                                    :class="theme === 'dark' ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-100 hover:bg-gray-200'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                </svg>
                                <span class="text-sm font-medium" x-text="getThemeName(theme)"></span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 rounded-lg shadow-lg border z-50"
                                 :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
                                
                                <!-- Theme Categories -->
                                <div class="p-4">
                                    <h3 class="text-sm font-semibold mb-3"
                                        :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-900'">Choose Theme</h3>
                                    
                                    <!-- Professional Themes -->
                                    <div class="mb-4">
                                        <h4 class="text-xs font-medium uppercase tracking-wide mb-2 flex items-center"
                                            :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                            </svg>
                                            Professional
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button @click="switchTheme('light')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'light' ? 'border-blue-500 bg-blue-50' : (theme === 'dark' ? 'border-blue-500 bg-blue-900/20' : 'border-gray-200 hover:border-gray-300')">
                                                <div class="w-4 h-4 rounded-full bg-white border border-gray-300 mr-2"></div>
                                                <span class="text-xs font-medium"
                                                      :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Light</span>
                                            </button>
                                            <button @click="switchTheme('dark')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'dark' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-gray-800 border border-gray-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Dark</span>
                                            </button>
                                            <button @click="switchTheme('midnight')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'midnight' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-slate-900 border border-slate-700 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Midnight</span>
                                            </button>
                                            <button @click="switchTheme('high-contrast')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'high-contrast' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-black border border-gray-800 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">High Contrast</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Creative Themes -->
                                    <div class="mb-4">
                                        <h4 class="text-xs font-medium uppercase tracking-wide mb-2 flex items-center"
                                            :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                                            </svg>
                                            Creative
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button @click="switchTheme('ocean')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'ocean' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-cyan-500 border border-cyan-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Ocean</span>
                                            </button>
                                            <button @click="switchTheme('forest')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'forest' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-green-500 border border-green-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Forest</span>
                                            </button>
                                            <button @click="switchTheme('sunset')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'sunset' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-orange-500 border border-orange-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Sunset</span>
                                            </button>
                                            <button @click="switchTheme('purple-dream')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'purple-dream' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-purple-500 border border-purple-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Purple</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Elegant Themes -->
                                    <div>
                                        <h4 class="text-xs font-medium uppercase tracking-wide mb-2 flex items-center"
                                            :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                            </svg>
                                            Elegant
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button @click="switchTheme('rose-gold')" 
                                                    class="flex items-center p-2 rounded-lg border transition-all duration-200"
                                                    :class="theme === 'rose-gold' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
                                                <div class="w-4 h-4 rounded-full bg-rose-500 border border-rose-600 mr-2"></div>
                                                <span class="text-xs font-medium" :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-700'">Rose Gold</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Status -->
                        <div class="flex items-center text-sm transition-colors duration-300"
                             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            System Online
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-3">
                            @yield('quick-actions')
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 transition-colors duration-300"
                  :class="theme === 'dark' ? 'bg-gray-900' : 'bg-gray-50'">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Flash Messages --}}
    <x-flash-messages />

    @stack('scripts')

    <!-- Alpine.js for theme management -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminLayout', () => ({
            theme: '{{ auth()->user()->theme_preference ?? "light" }}',
            
            init() {
                // Set initial theme
                this.applyTheme(this.theme);
            },
            
            async switchTheme(newTheme) {
                this.theme = newTheme;
                this.applyTheme(newTheme);
                
                try {
                    const response = await fetch('{{ route("admin.theme.switch") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ theme: newTheme })
                    });
                    
                    if (response.ok) {
                        console.log('Theme updated successfully');
                    }
                } catch (error) {
                    console.error('Failed to update theme:', error);
                }
            },
            
            applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                document.body.setAttribute('data-theme', theme);
            },
            
            getThemeName(themeKey) {
                const themeNames = {
                    'light': 'Light',
                    'dark': 'Dark',
                    'ocean': 'Ocean',
                    'forest': 'Forest',
                    'sunset': 'Sunset',
                    'purple-dream': 'Purple',
                    'midnight': 'Midnight',
                    'rose-gold': 'Rose Gold',
                    'high-contrast': 'High Contrast'
                };
                return themeNames[themeKey] || 'Light';
            }
        }));
    });

    // Auto-slug generation
    if (document.getElementById('title') && document.getElementById('slug')) {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('keyup', function() {
            const slug = this.value.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^¿-\u1FFF\u2C00-\uD7FF\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
            slugInput.value = slug;
        });
    }
    </script>
</body>
</html>
