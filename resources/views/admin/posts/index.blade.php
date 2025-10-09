@extends('layouts.admin')

@section('title', 'Posts Management')
@section('subtitle', 'Create, edit, and manage your blog posts')

@section('quick-actions')
<a href="{{ route('admin.posts.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
    </svg>
    Add Post
</a>
@endsection

@section('content')

        {{-- Bulk Action Bar --}}
        <div id="bulk-action-bar" class="hidden bg-blue-600 text-white p-4 rounded-lg shadow-lg mb-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="font-semibold"><span id="selected-count">0</span> items selected</span>
                <div class="flex gap-2">
                    <button data-bulk-action="publish" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-sm font-medium transition text-white">
                        Publish
                    </button>
                    <button data-bulk-action="draft" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md text-sm font-medium transition text-white">
                        Set as Draft
                    </button>
                    <button data-bulk-action="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-md text-sm font-medium transition text-white">
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

        <div class="overflow-hidden shadow-lg sm:rounded-lg border transition-colors duration-300"
             :class="theme === 'dark' ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'">
            <div class="p-6">
                <table class="min-w-full divide-y transition-colors duration-300"
                       :class="theme === 'dark' ? 'divide-gray-700' : 'divide-gray-200'">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Publish Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y transition-colors duration-300"
                           :class="theme === 'dark' ? 'divide-gray-700' : 'divide-gray-200'">
                        @foreach ($posts as $post)
                        <tr class="transition-colors duration-300"
                            :class="theme === 'dark' ? 'hover:bg-gray-700/50' : 'hover:bg-gray-50'">
                            <td class="px-6 py-4">
                                <input type="checkbox" value="{{ $post->id }}" class="bulk-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 text-sm transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-300' : 'text-gray-900'">{{ $post->title }}</td>
                            <td class="px-6 py-4 text-sm transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">{{ $post->category->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($post->status == 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-300"
                                          :class="theme === 'dark' ? 'bg-green-900/50 text-green-400' : 'bg-green-100 text-green-800'">Published</span>
                                @elseif($post->status == 'scheduled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-300"
                                          :class="theme === 'dark' ? 'bg-yellow-900/50 text-yellow-400' : 'bg-yellow-100 text-yellow-800'">Scheduled</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors duration-300"
                                          :class="theme === 'dark' ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-600'">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm transition-colors duration-300"
                                :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-600'">
                                @if($post->status == 'scheduled' && $post->published_at)
                                    <span class="text-yellow-600" title="{{ $post->published_at->format('Y-m-d H:i:s') }}">
                                        {{ $post->published_at->diffForHumans() }}
                                    </span>
                                @elseif($post->status == 'published' && $post->published_at)
                                    <span title="{{ $post->published_at->format('Y-m-d H:i:s') }}">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </span>
                                @else
                                    <span class="transition-colors duration-300"
                                          :class="theme === 'dark' ? 'text-gray-400' : 'text-gray-500'">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-300">Edit</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-300">Delete</button>
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