@extends('layouts.public')

@section('title', $page->title . ' - Professional CMS Solutions')

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
            <!-- Page Header -->
            <div class="text-center mb-12">
                <div class="mb-6">
                    <span class="inline-block bg-accent/10 text-accent text-sm font-semibold px-4 py-2 rounded-full">
                        {{ ucfirst($page->slug) }}
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight">
                    {{ $page->title }}
                </h1>
                
                @if($page->meta_description)
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    {{ $page->meta_description }}
                </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-surface/50 backdrop-blur-sm rounded-3xl border border-border/50 p-8 md:p-12 shadow-xl">
                    <div class="enhanced-prose max-w-none">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Home CTA -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-6">
                    Questions About Our 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Policies?</span>
                </h2>
                <p class="text-xl text-text-secondary mb-8">
                    We're here to help. Contact us if you have any questions about our policies or services.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact.create') }}" 
                       class="group inline-flex items-center px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Us
                    </a>
                    <a href="{{ route('home') }}" 
                       class="group inline-flex items-center px-8 py-4 bg-surface/50 text-text-primary rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-surface hover:scale-105 hover:shadow-xl border border-border/50">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Enhanced Prose Styling for Better Readability */
    .enhanced-prose {
        color: inherit;
        font-size: 18px;
        line-height: 1.8;
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