@extends('layouts.public')

@section('title', 'Blog')

@section('content')
    <h1 style="margin-bottom: 20px; font-size: 2em;">My Blog</h1>

    @forelse ($posts as $post)
        <article style="margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
            @if($post->featuredImage)
                <a href="{{ route('blog.show', $post) }}">
                    <img src="{{ Storage::url($post->featuredImage->path) }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" style="width:100%; height:auto; margin-bottom: 15px;">
                </a>
            @endif
            <h2>
                <a href="{{ route('blog.show', $post) }}" style="text-decoration: none; color: #222; font-size: 1.5em;">{{ $post->title }}</a>
            </h2>
            <div style="color: #666; font-size: 0.9em; margin-bottom: 10px;">
                Posted in <a href="{{ route('blog.category', $post->category) }}" style="color: #007bff;">{{ $post->category->name }}</a> on {{ $post->created_at->format('M d, Y') }}
            </div>
            <p style="line-height: 1.6;">{{ $post->excerpt }}</p>
            <a href="{{ route('blog.show', $post) }}" style="font-weight: bold; color: #007bff;">Read More &rarr;</a>
        </article>
    @empty
        <p>No posts found.</p>
    @endforelse

    <div>
        {{ $posts->links() }}
    </div>
@endsection