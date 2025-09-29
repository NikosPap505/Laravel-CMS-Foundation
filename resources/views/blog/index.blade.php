@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <h1 class="text-4xl font-bold mb-8">My Blog</h1>

    <div class="space-y-12">
        @forelse ($posts as $post)
            <article class="bg-white rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-xl">
                @if($post->featured_image)
                    <a href="{{ route('blog.show', $post) }}">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover">
                    </a>
                @endif
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2">
                        <span>Posted in </span>
                        <a href="{{ route('blog.category', $post->category) }}" class="text-blue-600 hover:underline">{{ $post->category->name }}</a>
                        <span> on {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <h2 class="text-2xl font-bold mb-3">
                        <a href="{{ route('blog.show', $post) }}" class="text-gray-800 hover:text-blue-600 transition duration-300">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>
                    <a href="{{ route('blog.show', $post) }}" class="font-semibold text-blue-600 hover:underline">Read More &rarr;</a>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-lg shadow-md p-6">
                <p>No posts found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
@endsection