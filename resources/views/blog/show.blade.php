@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article>
        <h1 style="font-size: 2.5em; margin-bottom: 10px;">{{ $post->title }}</h1>
        <div style="color: #666; font-size: 0.9em; margin-bottom: 20px;">
            Posted in <a href="{{ route('blog.category', $post->category) }}" style="color: #007bff;">{{ $post->category->name }}</a> on {{ $post->created_at->format('M d, Y') }}
        </div>
        
        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width:100%; height:auto; margin-bottom: 20px;">
        @endif

        <div class="post-body" style="line-height: 1.7;">
            {{-- Using {!! !!} is crucial to render HTML from the rich text editor --}}
            {!! $post->body !!}
        </div>
    </article>

    <a href="{{ route('blog.index') }}" style="display: inline-block; margin-top: 40px;">&larr; Back to Blog</a>
@endsection