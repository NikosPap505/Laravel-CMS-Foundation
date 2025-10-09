@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', now()->format('l, F j, Y') . ' • ' . now()->format('H:i'))

@section('quick-actions')
<a href="{{ route('admin.posts.create') }}" class="btn-secondary">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    New Post
</a>
<a href="{{ route('admin.ai.index') }}" class="btn-primary">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
    </svg>
    AI Assistant
</a>
@endsection

@section('content')
<!-- System Status Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Posts Status -->
    <div class="stats-card rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700 hover:shadow-gray-600/20' : 'bg-white border border-gray-200 hover:shadow-md'">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-md flex items-center justify-center mr-3 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-blue-900/50' : 'bg-blue-100'">
                    <svg class="w-4 h-4 transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-blue-400' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                        <span class="text-sm font-medium transition-colors duration-300"
                              :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-700'">Posts</span>
            </div>
            <span class="text-2xl font-bold transition-colors duration-300"
                  :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">{{ $totalPosts }}</span>
        </div>
        <div class="flex justify-between text-xs mb-2 transition-colors duration-300"
             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
            <span>{{ $publishedPosts }} published</span>
            <span>{{ $draftPosts }} draft</span>
            <span>{{ $scheduledPosts }} scheduled</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1">
            <div class="bg-blue-600 h-1 rounded-full" style="width: {{ $totalPosts > 0 ? ($publishedPosts / $totalPosts) * 100 : 0 }}%"></div>
        </div>
    </div>

    <!-- Pages Status -->
    <div class="stats-card rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700 hover:shadow-gray-600/20' : 'bg-white border border-gray-200 hover:shadow-md'">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-md flex items-center justify-center mr-3 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-green-900/50' : 'bg-green-100'">
                    <svg class="w-4 h-4 transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-green-400' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                        <span class="text-sm font-medium transition-colors duration-300"
                              :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-700'">Pages</span>
            </div>
            <span class="text-2xl font-bold transition-colors duration-300"
                  :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">{{ $totalPages }}</span>
        </div>
        <div class="text-xs mb-2 transition-colors duration-300"
             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
            Static content pages
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1">
            <div class="bg-green-600 h-1 rounded-full" style="width: 100%"></div>
        </div>
    </div>

    <!-- Media Status -->
    <div class="stats-card rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700 hover:shadow-gray-600/20' : 'bg-white border border-gray-200 hover:shadow-md'">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-md flex items-center justify-center mr-3 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-purple-900/50' : 'bg-purple-100'">
                    <svg class="w-4 h-4 transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-purple-400' : 'text-purple-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                        <span class="text-sm font-medium transition-colors duration-300"
                              :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-700'">Media</span>
            </div>
            <span class="text-2xl font-bold transition-colors duration-300"
                  :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">{{ $totalMedia }}</span>
        </div>
        <div class="text-xs mb-2 transition-colors duration-300"
             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
            {{ $storageUsedMB }} MB used
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1">
            <div class="bg-purple-600 h-1 rounded-full" style="width: 75%"></div>
        </div>
    </div>

    <!-- Users Status -->
    <div class="stats-card rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-300"
         :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700 hover:shadow-gray-600/20' : 'bg-white border border-gray-200 hover:shadow-md'">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-md flex items-center justify-center mr-3 transition-colors duration-300"
                     :class="theme === 'dark' ? 'bg-orange-900/50' : 'bg-orange-100'">
                    <svg class="w-4 h-4 transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-orange-400' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium transition-colors duration-300"
                      :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-700'">Users</span>
            </div>
            <span class="text-2xl font-bold transition-colors duration-300"
                  :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">{{ $totalUsers }}</span>
        </div>
        <div class="text-xs mb-2 transition-colors duration-300"
             :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
            Registered users
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1">
            <div class="bg-orange-600 h-1 rounded-full" style="width: 100%"></div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Posts -->
    <div class="lg:col-span-2">
        <div class="rounded-lg shadow-sm transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
            <div class="px-6 py-4 border-b transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold transition-colors duration-300"
                        :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Recent Posts</h3>
                    <a href="{{ route('admin.posts.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        View all →
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recentPosts as $post)
                    <div class="flex items-center justify-between py-3 border-b transition-colors duration-300 last:border-0"
                         :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-100'">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium mb-1 transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-100' : 'text-gray-900'">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="hover:text-blue-600">
                                    {{ Str::limit($post->title, 45) }}
                                </a>
                            </h4>
                            <div class="flex items-center text-xs transition-colors duration-300"
                                 :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                                <span class="mr-3">{{ $post->category->name ?? 'Uncategorized' }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div>
                            @if($post->status === 'published')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                                      :class="theme === 'dark' ? 'bg-green-900/50 text-green-400' : 'bg-green-100 text-green-800'">
                                    Published
                                </span>
                            @elseif($post->status === 'scheduled')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                                      :class="theme === 'dark' ? 'bg-yellow-900/50 text-yellow-400' : 'bg-yellow-100 text-yellow-800'">
                                    Scheduled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                                      :class="theme === 'dark' ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-600'">
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 transition-colors duration-300"
                         :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No posts yet.</p>
                        <a href="{{ route('admin.posts.create') }}" class="text-sm text-blue-600 hover:text-blue-700">Create your first post</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="rounded-lg shadow-sm transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
            <div class="px-4 py-3 border-b transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <h3 class="text-sm font-semibold transition-colors duration-300"
                    :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Quick Actions</h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.posts.create') }}" class="flex items-center p-2 text-sm rounded-md transition-colors duration-300"
                   :class="theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-50'">
                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Post
                </a>
                <a href="{{ route('admin.pages.create') }}" class="flex items-center p-2 text-sm rounded-md transition-colors duration-300"
                   :class="theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-50'">
                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    New Page
                </a>
                <a href="{{ route('admin.media.index') }}" class="flex items-center p-2 text-sm rounded-md transition-colors duration-300"
                   :class="theme === 'dark' ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-50'">
                    <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Upload Media
                </a>
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="rounded-lg shadow-sm transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
            <div class="px-4 py-3 border-b transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold transition-colors duration-300"
                        :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">Categories</h3>
                    <a href="{{ route('admin.categories.index') }}" class="text-xs text-blue-600 hover:text-blue-700">View all</a>
                </div>
            </div>
            <div class="p-4 space-y-3">
                @forelse($popularCategories->take(5) as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-600">{{ substr($category->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium transition-colors duration-300"
                                 :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-900'">{{ $category->name }}</div>
                            <div class="text-xs transition-colors duration-300"
                                 :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">{{ $category->posts_count }} posts</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 transition-colors duration-300"
                     :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">
                    <p class="text-xs">No categories yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- System Status -->
        <div class="rounded-lg shadow-sm transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border border-gray-700' : 'bg-white border border-gray-200'">
            <div class="px-4 py-3 border-b transition-colors duration-300"
                 :class="theme === 'dark' ? 'border-gray-700' : 'border-gray-200'">
                <h3 class="text-sm font-semibold transition-colors duration-300"
                    :class="theme === 'dark' ? 'text-white' : 'text-gray-900'">System Status</h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm transition-colors duration-300"
                          :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-600'">Database</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                          :class="theme === 'dark' ? 'bg-green-900/50 text-green-400' : 'bg-green-100 text-green-800'">
                        Connected
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm transition-colors duration-300"
                          :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-600'">Cache</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                          :class="theme === 'dark' ? 'bg-green-900/50 text-green-400' : 'bg-green-100 text-green-800'">
                        Active
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm transition-colors duration-300"
                          :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-600'">Storage</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-300"
                          :class="theme === 'dark' ? 'bg-green-900/50 text-green-400' : 'bg-green-100 text-green-800'">
                        {{ $storageUsedMB }}MB
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm transition-colors duration-300"
                          :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-600'">Laravel</span>
                    <span class="text-xs transition-colors duration-300"
                          :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">{{ app()->version() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboard', () => ({
        theme: '{{ auth()->user()->theme_preference ?? "light" }}',
        
        init() {
            // Add dashboard animations
            this.addDashboardAnimations();
        },
        
        addDashboardAnimations() {
            // Animate stats cards on load
            const statCards = document.querySelectorAll('.stats-card');
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
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        }
    }));
});
</script>
@endpush