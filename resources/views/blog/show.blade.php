@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto">
        @if ($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto rounded-lg shadow-lg mb-8">
        @endif

        <div class="text-center mb-4">
            <a href="{{ route('blog.category', $post->category) }}" class="text-accent font-semibold hover:underline">{{ $post->category->name }}</a>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-text-primary text-center mb-4">{{ $post->title }}</h1>

        <div class="text-center text-text-secondary mb-8">
            Posted on {{ $post->created_at->format('F jS, Y') }}
        </div>

        {{-- This "prose" class from the typography plugin styles all the content from TinyMCE --}}
        <div class="prose prose-invert prose-lg max-w-none prose-a:text-accent hover:prose-a:text-opacity-80">
            {!! $post->body !!}
        </div>
    </article>

    <div class="text-center mt-12">
         <a href="{{ route('blog.index') }}" class="inline-block px-6 py-3 bg-surface border border-border text-text-secondary rounded-md hover:bg-border hover:text-text-primary transition-colors">&larr; Back to Blog</a>
    </div>
@endsection