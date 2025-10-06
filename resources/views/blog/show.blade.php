@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto">
        {{-- Breadcrumbs --}}
        {{ Breadcrumbs::render('blog.show', $post) }}
        
        @if ($post->featuredImage)
            <img src="{{ Storage::url($post->featuredImage->path) }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" class="w-full h-auto rounded-lg shadow-lg mb-8">
        @endif
        
        <div class="text-center mb-4">
            <a href="{{ route('blog.category', $post->category) }}" class="text-accent font-semibold hover:underline">{{ $post->category->name }}</a>
            @if($post->tags->count() > 0)
                <div class="mt-2">
                    @foreach($post->tags as $tag)
                        <span class="inline-block px-2 py-1 text-xs rounded-full mr-1" style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold text-text-primary text-center mb-4">{{ $post->title }}</h1>
        
        <div class="text-center text-text-secondary mb-8">
            Posted on {{ $post->created_at->format('F jS, Y') }} • {{ $post->view_count }} views
        </div>
        
        {{-- This "prose" class from the typography plugin styles all the content from TinyMCE --}}
        <div class="prose prose-invert prose-lg max-w-none prose-a:text-accent hover:prose-a:text-opacity-80">
            {!! $post->body !!}
        </div>
    </article>

    {{-- Related Posts Section --}}
    {{-- Comments Section --}}
    @include('components.comment-section', ['post' => $post, 'comments' => $comments])

    @if($relatedPosts->count() > 0)
    <section class="mt-16 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-text-primary mb-6">Related Posts</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedPosts as $related)
            <article class="bg-surface rounded-lg shadow-md overflow-hidden border border-border hover:border-accent transition-colors">
                @if($related->featuredImage)
                <a href="{{ route('blog.show', $related) }}">
                    <img src="{{ Storage::url($related->featuredImage->path) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover">
                </a>
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">
                        <a href="{{ route('blog.show', $related) }}" class="text-text-primary hover:text-accent transition">
                            {{ Str::limit($related->title, 50) }}
                        </a>
                    </h3>
                    <p class="text-sm text-text-secondary mb-3">
                        {{ Str::limit($related->excerpt, 80) }}
                    </p>
                    <a href="{{ route('blog.show', $related) }}" class="text-sm text-accent hover:underline">
                        Read More &rarr;
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    <div class="text-center mt-12">
         <a href="{{ route('blog.index') }}" class="inline-block px-6 py-3 bg-surface border border-border text-text-secondary rounded-md hover:bg-border hover:text-text-primary transition-colors">&larr; Back to Blog</a>
    </div>


@endsection