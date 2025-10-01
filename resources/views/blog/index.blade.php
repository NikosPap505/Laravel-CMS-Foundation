@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <div class="py-16 sm:py-20">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-text-primary mb-2">From the Blog</h1>
            <p class="text-lg text-text-secondary">Stay up to date with the latest news and insights from our team.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <article class="bg-surface rounded-lg shadow-lg overflow-hidden flex flex-col border border-border 
                                transform transition-all duration-300 hover:shadow-2xl hover:border-accent">
                    @if($post->featured_image)
                        <a href="{{ route('blog.show', $post) }}">
                            {{-- THE FIX IS IN THIS LINE --}}
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        </a>
                    @endif
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="text-sm text-text-secondary mb-2">
                            <a href="{{ route('blog.category', $post->category) }}" class="text-accent hover:underline font-semibold">{{ $post->category->name }}</a>
                        </div>
                        <h2 class="text-xl font-bold mb-3 flex-grow">
                            <a href="{{ route('blog.show', $post) }}" class="text-text-primary hover:text-accent transition duration-300">{{ $post->title }}</a>
                        </h2>
                        <p class="text-text-secondary mb-4 text-sm">{{ $post->excerpt }}</p>
                        <div class="mt-auto pt-4 border-t border-border">
                             <a href="{{ route('blog.show', $post) }}" class="text-sm font-semibold text-accent hover:underline">Read More &rarr;</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-surface rounded-lg shadow-md p-6 md:col-span-2 lg:col-span-3">
                    <p class="text-center text-text-secondary">No posts found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection