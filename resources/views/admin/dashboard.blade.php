@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Dashboard</h1>
            <p class="text-text-secondary">Welcome back! Here's what's happening with your site.</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Total Posts --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Total Posts</p>
                        <p class="text-3xl font-bold text-text-primary">{{ $totalPosts }}</p>
                    </div>
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2 text-xs">
                    <span class="px-2 py-1 bg-success/20 text-success rounded">{{ $publishedPosts }} Published</span>
                    <span class="px-2 py-1 bg-warning/20 text-warning rounded">{{ $scheduledPosts }} Scheduled</span>
                    <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded">{{ $draftPosts }} Draft</span>
                </div>
            </div>

            {{-- Total Pages --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Total Pages</p>
                        <p class="text-3xl font-bold text-text-primary">{{ $totalPages }}</p>
                    </div>
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Media --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Media Files</p>
                        <p class="text-3xl font-bold text-text-primary">{{ $totalMedia }}</p>
                    </div>
                    <div class="p-3 bg-purple-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-text-secondary mt-4">{{ $storageUsedMB }} MB used</p>
            </div>

            {{-- Total Users --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary">Users</p>
                        <p class="text-3xl font-bold text-text-primary">{{ $totalUsers }}</p>
                    </div>
                    <div class="p-3 bg-yellow-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Recent Posts --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Recent Posts</h2>
                <div class="space-y-3">
                    @forelse($recentPosts as $post)
                        <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                            <div class="flex-1">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm font-medium text-text-primary hover:text-accent">
                                    {{ Str::limit($post->title, 40) }}
                                </a>
                                <p class="text-xs text-text-secondary">
                                    {{ $post->category->name }} • {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div>
                                @if($post->status == 'published')
                                    <span class="px-2 py-1 text-xs bg-success/20 text-success rounded">Published</span>
                                @elseif($post->status == 'scheduled')
                                    <span class="px-2 py-1 text-xs bg-warning/20 text-warning rounded">Scheduled</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-500/20 text-gray-400 rounded">Draft</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-text-secondary">No posts yet.</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.posts.index') }}" class="text-sm text-accent hover:underline">View all posts →</a>
                </div>
            </div>

            {{-- Popular Posts --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Popular Posts (30 days)</h2>
                <div class="space-y-3">
                    @forelse($popularPosts as $post)
                        <div class="flex items-center justify-between py-2 border-b border-border last:border-0">
                            <div class="flex-1">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-sm font-medium text-text-primary hover:text-accent">
                                    {{ Str::limit($post->title, 40) }}
                                </a>
                                <p class="text-xs text-text-secondary">
                                    {{ $post->category->name }} • {{ $post->view_count }} views
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-text-secondary">No popular posts yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Popular Categories --}}
            <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Popular Categories</h2>
                <div class="space-y-3">
                    @forelse($popularCategories as $category)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                                    <span class="text-accent font-bold">{{ substr($category->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-sm font-medium text-text-primary hover:text-accent">
                                        {{ $category->name }}
                                    </a>
                                    <p class="text-xs text-text-secondary">{{ $category->posts_count }} posts</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-text-secondary">No categories yet.</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="text-sm text-accent hover:underline">View all categories →</a>
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
            <h2 class="text-lg font-semibold text-text-primary mb-4">Recent Notifications</h2>
            <div class="space-y-3">
                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                    <div class="flex items-start space-x-3 py-3 border-b border-border last:border-0">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-text-primary font-medium">{{ $notification->data['title'] }}</p>
                            <p class="text-xs text-text-secondary">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-text-secondary">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-text-secondary">No new notifications.</p>
                @endforelse
            </div>
            @if(auth()->user()->unreadNotifications->count() > 5)
                <div class="mt-4">
                    <a href="#" class="text-sm text-accent hover:underline">View all notifications →</a>
                </div>
            @endif
        </div>

        {{-- Recent Activity --}}
        <div class="bg-surface p-6 rounded-lg border border-border shadow-lg">
            <h2 class="text-lg font-semibold text-text-primary mb-4">Recent Activity</h2>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                    <div class="flex items-start space-x-3 py-3 border-b border-border last:border-0">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-text-primary">
                                <span class="font-medium">{{ $activity->causer->name ?? 'System' }}</span>
                                {{ $activity->description }}
                                @if($activity->subject)
                                    <span class="text-accent">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                @endif
                            </p>
                            <p class="text-xs text-text-secondary">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-text-secondary">No recent activity.</p>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg shadow-lg transition-colors flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">New Post</span>
            </a>
            <a href="{{ route('admin.pages.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg shadow-lg transition-colors flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">New Page</span>
            </a>
            <a href="{{ route('admin.media.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg shadow-lg transition-colors flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span class="font-semibold">Upload Media</span>
            </a>
        </div>
    </div>
</div>
@endsection

