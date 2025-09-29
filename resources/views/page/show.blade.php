@extends('layouts.public')

@section('title', $page->title)

@section('content')
    <div class="bg-surface rounded-lg shadow-md overflow-hidden border border-border">
        <div class="p-6 md:p-10 lg:p-12">
            {{-- This "prose" class from the typography plugin styles all the content from TinyMCE --}}
            <div class="prose prose-invert prose-lg max-w-none prose-a:text-accent hover:prose-a:text-opacity-80">
                <h1 class="text-4xl font-bold text-text-primary mb-6">{{ $page->title }}</h1>
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection