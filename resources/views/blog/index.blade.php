@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <div class="py-16 sm:py-20">
        {{-- Breadcrumbs --}}
        {{ Breadcrumbs::render('blog.index') }}
        
        <div class="text-center">
            <h1 class="text-4xl font-bold text-text-primary mb-2">From the Blog</h1>
            <p class="text-lg text-text-secondary">Stay up to date with the latest news and insights from our team.</p>
        </div>

        <div class="mt-8 max-w-lg mx-auto">
            <form action="{{ route('blog.index') }}" method="GET" class="flex">
                <input type="text" name="search" placeholder="Search for posts..." class="w-full px-4 py-2 rounded-l-md border border-border focus:outline-none focus:ring-2 focus:ring-accent bg-surface text-text-primary" value="{{ request('search') }}">
                <button type="submit" class="px-4 py-2 bg-accent text-white rounded-r-md hover:bg-opacity-80">Search</button>
            </form>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <article class="bg-surface rounded-lg shadow-lg overflow-hidden flex flex-col border border-border 
                                transform transition-all duration-300 hover:shadow-2xl hover:border-accent">
                    @if($post->featuredImage)
                        <a href="{{ route('blog.show', $post) }}">
                            {{-- THE FIX IS IN THIS LINE --}}
                            <img src="{{ Storage::url($post->featuredImage->path) }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" class="w-full h-48 object-cover">
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
                    @if (request('search'))
                        <p class="text-center text-text-secondary">No results found for "<strong class="text-text-primary">{{ request('search') }}</strong>".</p>
                    @else
                        <p class="text-center text-text-secondary">No posts found.</p>
                    @endif
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
@endsection