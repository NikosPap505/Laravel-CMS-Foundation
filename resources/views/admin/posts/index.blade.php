@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Posts</h1>
            <a href="{{ route('admin.posts.create') }}" class="btn-primary">Add Post</a>
        </div>

        {{-- Bulk Action Bar --}}
        <div id="bulk-action-bar" class="hidden bg-blue-600 text-white p-4 rounded-lg shadow-lg mb-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="font-semibold"><span id="selected-count">0</span> items selected</span>
                <div class="flex gap-2">
                    <button data-bulk-action="publish" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-sm font-medium transition">
                        Publish
                    </button>
                    <button data-bulk-action="draft" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-sm font-medium transition">
                        Set as Draft
                    </button>
                    <button data-bulk-action="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-sm font-medium transition">
                        Delete
                    </button>
                </div>
            </div>
            <button onclick="window.bulkActions.clearSelection()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-border text-accent focus:ring-accent">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Publish Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($posts as $post)
                        <tr class="hover:bg-background/50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $post->id }}" class="bulk-checkbox rounded border-border text-accent focus:ring-accent">
                            </td>
                            <td class="px-6 py-4 text-sm text-text-primary">{{ $post->title }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">{{ $post->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-text-secondary">
                                @if($post->status == 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/20 text-success">Published</span>
                                @elseif($post->status == 'scheduled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning/20 text-warning">Scheduled</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-500/20 text-gray-400">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-text-secondary">
                                @if($post->status == 'scheduled' && $post->published_at)
                                    <span class="text-warning" title="{{ $post->published_at->format('Y-m-d H:i:s') }}">
                                        {{ $post->published_at->diffForHumans() }}
                                    </span>
                                @elseif($post->status == 'published' && $post->published_at)
                                    <span title="{{ $post->published_at->format('Y-m-d H:i:s') }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="link-edit">Edit</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection