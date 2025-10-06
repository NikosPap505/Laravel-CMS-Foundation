@extends('layouts.public')

@section('title', 'Blog - Professional CMS Solutions')

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
        <div class="relative z-10 container mx-auto px-4 text-center">
            <!-- Breadcrumbs -->
            <div class="mb-8">
                {{ Breadcrumbs::render('blog.index') }}
            </div>
            
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-8 animate-fade-in">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                </svg>
                Latest Content
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight animate-slide-up">
                From Our 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70 animate-gradient">
                    Blog
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-text-secondary max-w-3xl mx-auto mb-12 leading-relaxed animate-slide-up-delay">
                Discover insights, tutorials, and industry trends powered by our professional CMS. 
                Stay ahead with the latest in content management and web development.
            </p>
            
            <!-- Search and Filter Form -->
            <div class="max-w-4xl mx-auto animate-slide-up-delay-2">
                <form action="{{ route('blog.index') }}" method="GET" class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-6 shadow-xl">
                    <!-- Search Bar -->
                    <div class="flex mb-4">
                        <input type="text" name="search" placeholder="Search for posts..." 
                               class="flex-1 px-6 py-4 bg-transparent text-text-primary placeholder-text-secondary focus:outline-none rounded-l-2xl border border-border/50" 
                               value="{{ request('search') }}">
                        <button type="submit" class="px-6 py-4 bg-accent text-white hover:bg-accent/90 transition-colors duration-300 rounded-r-2xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-text-secondary">Category:</label>
                            <select name="category" class="px-4 py-2 bg-background/50 border border-border/50 rounded-lg text-text-primary focus:outline-none focus:ring-2 focus:ring-accent">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-text-secondary">Sort by:</label>
                            <select name="sort" class="px-4 py-2 bg-background/50 border border-border/50 rounded-lg text-text-primary focus:outline-none focus:ring-2 focus:ring-accent">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="px-4 py-2 bg-accent/10 text-accent rounded-lg hover:bg-accent/20 transition-colors duration-300 text-sm font-medium">
                            Apply Filters
                        </button>
                        
                        @if(request('search') || request('category') || request('sort'))
                            <a href="{{ route('blog.index') }}" class="px-4 py-2 bg-surface text-text-secondary rounded-lg hover:bg-border transition-colors duration-300 text-sm font-medium">
                                Clear All
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($posts as $post)
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
                        
                        <div class="p-8">
                            <!-- Category Badge -->
                            <div class="mb-4">
                                <a href="{{ route('blog.category', $post->category) }}" 
                                   class="inline-block bg-accent/10 text-accent text-sm font-semibold px-3 py-1 rounded-full hover:bg-accent/20 transition-colors duration-300">
                                    {{ $post->category->name }}
                                </a>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-accent transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <!-- Excerpt -->
                            <p class="text-text-secondary mb-6 leading-relaxed">{{ $post->excerpt }}</p>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-sm text-text-secondary mb-6">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $post->published_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $post->view_count }} views
                                </span>
                            </div>
                            
                            <!-- Read More Button -->
                            <a href="{{ route('blog.show', $post) }}" 
                               class="group/btn inline-flex items-center text-accent font-semibold hover:text-accent/80 transition-colors duration-300">
                                Read More
                                <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 lg:col-span-3">
                        <div class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-12 text-center">
                            @if (request('search'))
                                <div class="text-6xl mb-6">🔍</div>
                                <h3 class="text-2xl font-bold text-text-primary mb-4">No Results Found</h3>
                                <p class="text-text-secondary mb-6">No posts found for "<strong class="text-accent">{{ request('search') }}</strong>".</p>
                                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 bg-accent text-white rounded-lg font-semibold hover:bg-accent/90 transition-colors duration-300">
                                    View All Posts
                                </a>
                            @else
                                <div class="text-6xl mb-6">📝</div>
                                <h3 class="text-2xl font-bold text-text-primary mb-4">No Posts Yet</h3>
                                <p class="text-text-secondary">Check back soon for amazing content!</p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-16 flex justify-center">
                    <div class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Custom Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    
    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }
    
    .animate-slide-up-delay {
        animation: slideUp 0.8s ease-out 0.2s both;
    }
    
    .animate-slide-up-delay-2 {
        animation: slideUp 0.8s ease-out 0.4s both;
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
    
    /* Glassmorphism Effect */
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush