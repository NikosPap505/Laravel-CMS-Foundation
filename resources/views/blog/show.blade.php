@extends('layouts.public')

@section('title', $post->title . ' - Professional CMS Solutions')

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
            <!-- Breadcrumbs -->
            <div class="mb-8">
                {{ Breadcrumbs::render('blog.show', $post) }}
            </div>
            
            <!-- Featured Image -->
            @if ($post->featuredImage)
                <div class="mb-12">
                    <div class="relative overflow-hidden rounded-3xl shadow-2xl">
                        <img src="{{ Storage::url($post->featuredImage->path) }}" 
                             alt="{{ $post->featuredImage->alt_text ?? $post->title }}" 
                             class="w-full h-64 md:h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    </div>
                </div>
            @endif
            
            <!-- Article Header -->
            <div class="text-center mb-12">
                <!-- Category Badge -->
                <div class="mb-6">
                    <a href="{{ route('blog.category', $post->category) }}" 
                       class="inline-block bg-accent/10 text-accent text-sm font-semibold px-4 py-2 rounded-full hover:bg-accent/20 transition-colors duration-300">
                        {{ $post->category->name }}
                    </a>
                </div>
                
                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="mb-6 flex flex-wrap justify-center gap-2">
                        @foreach($post->tags as $tag)
                            <span class="inline-block bg-surface/50 backdrop-blur-sm text-text-secondary text-xs font-medium px-3 py-1 rounded-full border border-border/50">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
                
                <!-- Title -->
                <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight">
                    {{ $post->title }}
                </h1>
                
                <!-- Meta Info -->
                <div class="flex items-center justify-center space-x-6 text-text-secondary">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $post->created_at->format('F jS, Y') }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ $post->view_count }} views
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Table of Contents (Sidebar) -->
                    <div class="lg:col-span-1 order-2 lg:order-1">
                        <div class="sticky top-8">
                            <div class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-6 shadow-lg">
                                <h3 class="text-lg font-bold text-text-primary mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                    Table of Contents
                                </h3>
                                <div id="table-of-contents" class="space-y-2 text-sm">
                                    <!-- TOC will be populated by JavaScript -->
                                </div>
                                
                                <!-- Reading Progress -->
                                <div class="mt-6 pt-6 border-t border-border/50">
                                    <div class="flex items-center justify-between text-sm text-text-secondary mb-2">
                                        <span>Reading Progress</span>
                                        <span id="reading-progress-text">0%</span>
                                    </div>
                                    <div class="w-full bg-border/50 rounded-full h-2">
                                        <div id="reading-progress-bar" class="bg-accent h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <div class="mt-2 text-xs text-text-secondary">
                                        <span id="reading-time">~{{ ceil(str_word_count(strip_tags($post->body)) / 200) }} min read</span>
                                    </div>
                                </div>
                                
                                <!-- Social Sharing -->
                                <div class="mt-6 pt-6 border-t border-border/50">
                                    <h4 class="text-sm font-semibold text-text-primary mb-3">Share this article</h4>
                                    <div class="flex space-x-3">
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" 
                                           target="_blank" 
                                           class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                                           target="_blank" 
                                           class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                           target="_blank" 
                                           class="flex items-center justify-center w-8 h-8 bg-blue-800 text-white rounded-lg hover:bg-blue-900 transition-colors duration-300">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Article Content -->
                    <article class="lg:col-span-3 order-1 lg:order-2">
                        <div class="bg-surface/50 backdrop-blur-sm rounded-3xl border border-border/50 p-8 md:p-12 shadow-xl">
                            <div class="enhanced-prose max-w-none">
                                {!! $post->body !!}
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Comments Section -->
    @include('components.comment-section', ['post' => $post, 'comments' => $comments])

    <!-- Related Posts Section -->
    @if($relatedPosts->count() > 0)
    <section class="py-20 md:py-32 bg-gradient-to-br from-surface/30 to-background relative">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                    Related 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Articles</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                    Continue exploring with these related posts that might interest you.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($relatedPosts as $related)
                <article class="group bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 overflow-hidden transition-all duration-500 hover:border-accent/30 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    @if($related->featuredImage)
                    <a href="{{ route('blog.show', $related) }}" class="block">
                        <div class="relative overflow-hidden">
                            <img src="{{ Storage::url($related->featuredImage->path) }}" 
                                 alt="{{ $related->title }}" 
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    </a>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-text-primary mb-3 group-hover:text-accent transition-colors duration-300">
                            <a href="{{ route('blog.show', $related) }}" class="hover:text-accent transition-colors">
                                {{ Str::limit($related->title, 50) }}
                            </a>
                        </h3>
                        <p class="text-text-secondary mb-4 leading-relaxed">
                            {{ Str::limit($related->excerpt, 80) }}
                        </p>
                        <a href="{{ route('blog.show', $related) }}" 
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
        </div>
    </section>
    @endif

    <!-- Back to Blog CTA -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-6">
                    Explore More 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Content</span>
                </h2>
                <p class="text-xl text-text-secondary mb-8">
                    Discover more insights, tutorials, and industry trends from our blog.
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
    
    /* Simple but Effective Article Styling */
    .enhanced-prose {
        font-size: 18px;
        line-height: 1.8;
        color: var(--text-secondary);
        max-width: none;
    }
    
    /* Typography - High specificity to override TinyMCE */
    .enhanced-prose h1,
    .enhanced-prose h2,
    .enhanced-prose h3,
    .enhanced-prose h4,
    .enhanced-prose h5,
    .enhanced-prose h6 {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
        margin: 2rem 0 1rem 0 !important;
        line-height: 1.3 !important;
    }
    
    .enhanced-prose h1 { font-size: 2.5rem !important; }
    .enhanced-prose h2 { 
        font-size: 2rem !important; 
        border-bottom: 2px solid var(--accent) !important;
        padding-bottom: 0.5rem !important;
    }
    .enhanced-prose h3 { font-size: 1.5rem !important; }
    .enhanced-prose h4 { font-size: 1.25rem !important; }
    .enhanced-prose h5, .enhanced-prose h6 { font-size: 1.125rem !important; }
    
    /* Paragraphs */
    .enhanced-prose p {
        color: var(--text-secondary) !important;
        line-height: 1.8 !important;
        margin: 1.5rem 0 !important;
        font-size: 18px !important;
    }
    
    /* Links */
    .enhanced-prose a {
        color: var(--accent) !important;
        text-decoration: none !important;
        border-bottom: 1px solid transparent !important;
        transition: all 0.3s ease !important;
    }
    
    .enhanced-prose a:hover {
        border-bottom-color: var(--accent) !important;
    }
    
    /* Lists */
    .enhanced-prose ul, .enhanced-prose ol {
        margin: 1.5rem 0 !important;
        padding-left: 2rem !important;
    }
    
    .enhanced-prose li {
        margin: 0.75rem 0 !important;
        line-height: 1.7 !important;
        color: var(--text-secondary) !important;
    }
    
    /* Code */
    .enhanced-prose code {
        background: var(--surface) !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.375rem !important;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
        color: var(--accent) !important;
        border: 1px solid var(--border) !important;
    }
    
    .enhanced-prose pre {
        background: #1a1a1a !important;
        border-radius: 0.75rem !important;
        padding: 1.5rem !important;
        margin: 2rem 0 !important;
        border: 1px solid var(--border) !important;
        overflow-x: auto !important;
    }
    
    .enhanced-prose pre code {
        background: none !important;
        padding: 0 !important;
        border: none !important;
        color: #e2e8f0 !important;
    }
    
    /* Images */
    .enhanced-prose img {
        border-radius: 0.75rem !important;
        margin: 2rem 0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        max-width: 100% !important;
        height: auto !important;
    }
    
    /* Tables */
    .enhanced-prose table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 2rem 0 !important;
        background: var(--surface) !important;
        border-radius: 0.75rem !important;
        overflow: hidden !important;
    }
    
    .enhanced-prose th, .enhanced-prose td {
        padding: 1rem !important;
        border-bottom: 1px solid var(--border) !important;
    }
    
    .enhanced-prose th {
        background: var(--accent) !important;
        color: white !important;
        font-weight: 600 !important;
    }
    
    /* Blockquotes */
    .enhanced-prose blockquote {
        border-left: 4px solid var(--accent) !important;
        background: var(--surface) !important;
        padding: 2rem !important;
        margin: 2rem 0 !important;
        border-radius: 0.75rem !important;
        font-style: italic !important;
    }
    
    .enhanced-prose blockquote p {
        margin: 0 !important;
        color: var(--text-primary) !important;
    }
    
    /* Strong and Emphasis */
    .enhanced-prose strong {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
    }
    
    .enhanced-prose em {
        color: var(--text-primary) !important;
        font-style: italic !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate Table of Contents
    function generateTOC() {
        const tocContainer = document.getElementById('table-of-contents');
        const headings = document.querySelectorAll('.enhanced-prose h2, .enhanced-prose h3, .enhanced-prose h4');
        
        if (headings.length === 0) {
            tocContainer.innerHTML = '<p class="text-text-secondary text-sm">No headings found in this article.</p>';
            return;
        }
        
        let tocHTML = '';
        headings.forEach((heading, index) => {
            const id = `heading-${index}`;
            heading.id = id;
            
            const level = heading.tagName.toLowerCase();
            const indent = level === 'h3' ? 'ml-4' : level === 'h4' ? 'ml-8' : '';
            
            tocHTML += `
                <a href="#${id}" class="block py-1 text-text-secondary hover:text-accent transition-colors duration-300 ${indent} text-sm">
                    ${heading.textContent}
                </a>
            `;
        });
        
        tocContainer.innerHTML = tocHTML;
    }
    
    // Reading Progress Tracker
    function updateReadingProgress() {
        const article = document.querySelector('.enhanced-prose');
        const progressBar = document.getElementById('reading-progress-bar');
        const progressText = document.getElementById('reading-progress-text');
        
        if (!article || !progressBar) return;
        
        const articleTop = article.offsetTop;
        const articleHeight = article.offsetHeight;
        const windowHeight = window.innerHeight;
        const scrollTop = window.pageYOffset;
        
        const progress = Math.min(
            Math.max((scrollTop - articleTop + windowHeight / 2) / articleHeight, 0),
            1
        );
        
        const percentage = Math.round(progress * 100);
        progressBar.style.width = percentage + '%';
        progressText.textContent = percentage + '%';
    }
    
    // Smooth scroll for TOC links
    function initSmoothScroll() {
        document.querySelectorAll('#table-of-contents a[href^="#"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // Initialize everything
    generateTOC();
    initSmoothScroll();
    
    // Update reading progress on scroll
    let ticking = false;
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateReadingProgress);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', function() {
        requestTick();
        ticking = false;
    });
    
    // Initial progress update
    updateReadingProgress();
    
    // Add reading time calculation
    const wordCount = document.querySelector('.enhanced-prose').textContent.split(/\s+/).length;
    const readingTime = Math.ceil(wordCount / 200);
    const readingTimeElement = document.getElementById('reading-time');
    if (readingTimeElement) {
        readingTimeElement.textContent = `~${readingTime} min read`;
    }
    
    // Add copy code functionality for code blocks
    document.querySelectorAll('.enhanced-prose pre').forEach(pre => {
        const button = document.createElement('button');
        button.className = 'absolute top-2 right-2 bg-gray-700 text-white px-2 py-1 rounded text-xs hover:bg-gray-600 transition-colors duration-300';
        button.textContent = 'Copy';
        button.style.position = 'absolute';
        button.style.top = '0.5rem';
        button.style.right = '0.5rem';
        
        button.addEventListener('click', function() {
            const code = pre.querySelector('code').textContent;
            navigator.clipboard.writeText(code).then(() => {
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = 'Copy';
                }, 2000);
            });
        });
        
        pre.style.position = 'relative';
        pre.appendChild(button);
    });
    
    // Add print styles
    const printStyles = `
        @media print {
            .enhanced-prose {
                font-size: 12pt !important;
                line-height: 1.6 !important;
                color: black !important;
            }
            .enhanced-prose h1, .enhanced-prose h2, .enhanced-prose h3, .enhanced-prose h4, .enhanced-prose h5, .enhanced-prose h6 {
                color: black !important;
                page-break-after: avoid;
            }
            .enhanced-prose blockquote {
                border-left: 3px solid #333 !important;
                background: #f5f5f5 !important;
                color: black !important;
            }
            .enhanced-prose pre {
                background: #f5f5f5 !important;
                border: 1px solid #ccc !important;
                color: black !important;
            }
            .enhanced-prose table {
                border-collapse: collapse !important;
            }
            .enhanced-prose th, .enhanced-prose td {
                border: 1px solid #ccc !important;
                color: black !important;
            }
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = printStyles;
    document.head.appendChild(styleSheet);
});
</script>
@endpush