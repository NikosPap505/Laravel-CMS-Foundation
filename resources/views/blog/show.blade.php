@extends('layouts.public')

@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto">
        @if ($post->featuredImage)
            <img src="{{ Storage::url($post->featuredImage->path) }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" class="w-full h-auto rounded-lg shadow-lg mb-8">
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

    {{-- Disqus Comment Section --}}
    <section class="mt-16 max-w-4xl mx-auto">
        <div id="disqus_thread"></div>
        <script>
            var disqus_config = function () {
                this.page.url = '{{ url()->current() }}';
                this.page.identifier = 'post-{{ $post->id }}';
            };
            
            (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            // Make sure to replace 'mycms-blog' with your actual Disqus shortname
            s.src = 'https://mycms-blog.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    </section>

@endsection