@extends('layouts.public')

@section('title', $category->name . ' - Blog Category')

@php
    $breadcrumbs = Breadcrumbs::generate('blog.category', $category);
@endphp

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 md:py-32 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-background via-surface to-background">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f3f4f6" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-accent/10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-accent/20 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-12 h-12 bg-accent/15 rounded-full animate-pulse"></div>
        
        <!-- Main Content -->
        <div class="relative z-10 container mx-auto px-4">
            
            <!-- Category Header -->
            <div class="text-center mb-12">
                <div class="mb-6">
                    <span class="inline-block bg-accent/10 text-accent text-sm font-semibold px-4 py-2 rounded-full">
                        Category
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight">
                    {{ $category->name }}
                </h1>
                
                <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                    Explore all posts in the {{ $category->name }} category. Discover insights, tutorials, and industry trends.
                </p>
            </div>
        </div>
    </section>

    <!-- Posts Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                    @foreach($posts as $post)
                    <article class="group bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 overflow-hidden transition-all duration-500 hover:border-accent/30 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                        @if($post->featuredImage)
                        <a href="{{ route('blog.show', $post) }}" class="block">
                            <div class="relative overflow-hidden">
                                <img src="{{ Storage::url($post->featuredImage->path) }}" 
                                     alt="{{ $post->featuredImage->alt_text ?? $post->title }}" 
                                     class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </a>
                        @endif
                        
                        <div class="p-6">
                            <!-- Category Badge -->
                            <div class="mb-4">
                                <a href="{{ route('blog.category', $post->category) }}" 
                                   class="inline-block bg-accent/10 text-accent text-xs font-semibold px-3 py-1 rounded-full hover:bg-accent/20 transition-colors duration-300">
                                    {{ $post->category->name }}
                                </a>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="text-xl font-bold text-text-primary mb-3 group-hover:text-accent transition-colors duration-300">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-accent transition-colors">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                            </h2>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center space-x-4 text-sm text-text-secondary mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $post->created_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </span>
                            </div>
                            
                            <!-- Excerpt -->
                            <p class="text-text-secondary mb-4 leading-relaxed">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>
                            
                            <!-- Read More Link -->
                            <a href="{{ route('blog.show', $post) }}" 
                               class="group/btn inline-flex items-center text-accent font-semibold hover:text-accent/80 transition-colors duration-300">
                                Read More
                                <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-16 flex justify-center">
                    <div class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-accent/10 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-text-primary mb-4">No Posts Found</h3>
                        <p class="text-text-secondary mb-8">
                            There are no posts in the {{ $category->name }} category yet. Check back later for new content!
                        </p>
                        <a href="{{ route('blog.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-accent/90 transition-colors duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Blog
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Back to Blog CTA -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-6">
                    Explore More 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Categories</span>
                </h2>
                <p class="text-xl text-text-secondary mb-8">
                    Discover more insights and tutorials from other categories.
                </p>
                <a href="{{ route('blog.index') }}" 
                   class="group inline-flex items-center px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </section>
@endsection