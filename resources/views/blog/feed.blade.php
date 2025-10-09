@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} - Blog</title>
        <link>{{ url('/blog') }}</link>
        <description>Latest posts from {{ config('app.name') }}</description>
        <language>en-us</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />

        @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <link>{{ route('blog.show', $post) }}</link>
            <description><![CDATA[{{ $post->excerpt }}]]></description>
            <author>{{ config('app.name') }}</author>
            <category>{{ $post->category->name }}</category>
            <guid>{{ route('blog.show', $post) }}</guid>
            <pubDate>{{ $post->published_at->toRssString() }}</pubDate>
            @if($post->featuredImage)
            <enclosure url="{{ Storage::url($post->featuredImage->path) }}" type="{{ $post->featuredImage->mime_type }}" />
            @endif
        </item>
        @endforeach
    </channel>
</rss>
