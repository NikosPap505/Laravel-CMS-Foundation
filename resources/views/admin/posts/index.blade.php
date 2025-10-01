@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Posts</h1>
            <a href="{{ route('admin.posts.create') }}" class="btn-primary">Add Post</a>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($posts as $post)
                        <tr>
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