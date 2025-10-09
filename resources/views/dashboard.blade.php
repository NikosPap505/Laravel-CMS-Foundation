<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Welcome back! Here's what's happening with your CMS.</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    System Online
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Posts Card -->
                <div class="group relative bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50 dark:border-gray-700/50 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Post::count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Posts</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Blog Posts</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ \App\Models\Post::where('status', 'published')->count() }} published</p>
                    </div>
                </div>

                <!-- Comments Card -->
                <div class="group relative bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50 dark:border-gray-700/50 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Comment::count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Comments</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Comments</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ \App\Models\Comment::where('is_approved', true)->count() }} approved</p>
                    </div>
                </div>

                <!-- Pages Card -->
                <div class="group relative bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50 dark:border-gray-700/50 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Page::count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Pages</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Pages</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ \App\Models\Page::where('is_published', true)->count() }} published</p>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="group relative bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-white/50 dark:border-gray-700/50 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Users</p>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Users</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ \App\Models\User::where('email_verified_at', '!=', null)->count() }} verified</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Posts -->
                <div class="lg:col-span-2">
                    <div class="bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl shadow-lg border border-white/50 dark:border-gray-700/50 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Posts</h3>
                            <a href="{{ route('admin.posts.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                                View all
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        <div class="space-y-4">
                            @forelse(\App\Models\Post::with('category')->latest()->limit(5)->get() as $post)
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $post->title }}</h4>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium mr-3">{{ $post->category->name ?? 'Uncategorized' }}</span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    @if($post->status === 'published')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Draft
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p>No posts yet. <a href="{{ route('admin.posts.create') }}" class="text-blue-600 hover:text-blue-700">Create your first post</a></p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl shadow-lg border border-white/50 dark:border-gray-700/50 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.posts.create') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-xl transition-colors duration-200 group">
                                <div class="p-2 bg-blue-500 text-white rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">New Post</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Create a new blog post</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.pages.create') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-xl transition-colors duration-200 group">
                                <div class="p-2 bg-green-500 text-white rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">New Page</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Create a new page</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.integrations.index') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-xl transition-colors duration-200 group">
                                <div class="p-2 bg-purple-500 text-white rounded-lg mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Integration Hub</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Connect services</p>
                                </div>
                            </a>

                            <!-- AI Assistant Section -->
                            <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-4 border border-pink-200 dark:border-pink-800">
                                <div class="flex items-center mb-4">
                                    <div class="p-2 bg-pink-500 text-white rounded-lg mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">AI Assistant</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage AI tools</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <a href="{{ route('admin.ai.analytics') }}" class="flex items-center p-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group">
                                        <div class="p-1.5 bg-indigo-500 text-white rounded mr-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">Analytics</h5>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">AI usage insights</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('admin.ai.usage-page') }}" class="flex items-center p-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group">
                                        <div class="p-1.5 bg-blue-500 text-white rounded mr-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">Usage</h5>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">AI usage tracking</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white/90 backdrop-blur-sm dark:bg-gray-800/90 rounded-2xl shadow-lg border border-white/50 dark:border-gray-700/50 p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">System Status</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Database</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Connected
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Cache</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Active
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Storage</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Available
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Laravel Version</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->version() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Add some interactive animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats cards on load
        const statCards = document.querySelectorAll('.group.relative.bg-white\\/90, .group.relative.dark\\:bg-gray-800\\/90');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
        
        // Add hover effects to action cards
        const actionCards = document.querySelectorAll('.group');
        actionCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
