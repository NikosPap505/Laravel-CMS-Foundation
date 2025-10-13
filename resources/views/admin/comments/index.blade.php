@extends('layouts.admin')

@section('title', 'Comments Management')
@section('subtitle', 'Moderate and manage user comments')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-text-primary">Comments Management</h1>
        <div class="flex space-x-2">
            <span class="text-sm text-text-secondary">
                Total: {{ $comments->total() }} comments
            </span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-surface rounded-lg border border-border p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label for="status" class="block text-sm font-medium text-text-primary mb-2">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Comments</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
                </select>
            </div>
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-text-primary mb-2">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Search by content, author, or post title..."
                       class="w-full px-3 py-2 border border-border rounded-md bg-background text-text-primary">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Bulk Actions --}}
    @if($comments->count() > 0)
    <form id="bulk-form" method="POST" action="{{ route('admin.comments.bulk') }}" class="mb-4">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="action" id="bulk-action" class="px-3 py-2 border border-border rounded-md bg-background text-text-primary">
                <option value="">Bulk Actions</option>
                <option value="approve">Approve Selected</option>
                <option value="reject">Reject Selected</option>
                <option value="spam">Mark as Spam</option>
                <option value="delete">Delete Selected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent/90 transition-colors" onclick="return confirm('Are you sure?')">
                Apply
            </button>
        </div>
    </form>
    @endif

    {{-- Comments List --}}
    <div class="bg-surface rounded-lg border border-border overflow-hidden">
        @forelse($comments as $comment)
        <div class="border-b border-border last:border-b-0 p-6">
            <div class="flex items-start space-x-4">
                {{-- Checkbox for bulk actions --}}
                <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" form="bulk-form" class="mt-1">
                
                {{-- Comment Content --}}
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-text-primary">
                                {{ $comment->author_name ?? $comment->user->name ?? 'Anonymous' }}
                            </h3>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($comment->status === 'approved') bg-green-100 text-green-800
                                @elseif($comment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($comment->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($comment->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-text-secondary">
                            {{ $comment->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="text-text-secondary mb-3">
                        <strong>Post:</strong> 
                        @if($comment->post)
                            <a href="{{ route('blog.show', $comment->post) }}" class="text-accent hover:underline" target="_blank">
                                {{ $comment->post->title }}
                            </a>
                        @else
                            <span class="text-red-500 italic">Post not found or deleted</span>
                        @endif
                    </div>
                    
                    <div class="bg-background border border-border rounded-md p-3 mb-3">
                        <p class="text-text-primary">{{ $comment->content }}</p>
                    </div>
                    
                    @if($comment->author_email)
                    <div class="text-sm text-text-secondary mb-3">
                        <strong>Email:</strong> {{ $comment->author_email }}
                    </div>
                    @endif
                    
                    {{-- Action Buttons --}}
                    <div class="flex space-x-2">
                        @if($comment->status === 'pending')
                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                                    Approve
                                </button>
                            </form>
                        @endif
                        
                        @if($comment->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.comments.reject', $comment) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                                    Reject
                                </button>
                            </form>
                        @endif
                        
                        @if($comment->status !== 'spam')
                            <form method="POST" action="{{ route('admin.comments.spam', $comment) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700 transition-colors">
                                    Mark as Spam
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <p class="text-text-secondary text-lg">No comments found.</p>
            <p class="text-text-secondary text-sm mt-2">Comments will appear here when visitors submit them.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($comments->hasPages())
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
    @endif
</div>

<script>
// Select all checkbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.createElement('input');
    selectAllCheckbox.type = 'checkbox';
    selectAllCheckbox.id = 'select-all';
    selectAllCheckbox.className = 'mt-1';
    
    const bulkForm = document.getElementById('bulk-form');
    if (bulkForm) {
        const firstCheckbox = bulkForm.querySelector('input[type="checkbox"]');
        if (firstCheckbox) {
            firstCheckbox.parentNode.insertBefore(selectAllCheckbox, firstCheckbox);
            
            const label = document.createElement('label');
            label.htmlFor = 'select-all';
            label.textContent = 'Select All';
            label.className = 'text-sm text-text-primary ml-2';
            selectAllCheckbox.parentNode.insertBefore(label, selectAllCheckbox.nextSibling);
            
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="comment_ids[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    }
});
</script>
@endsection
