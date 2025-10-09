@extends('layouts.public')

@section('title', ($page->meta_title ?? 'About Us') . ' - Professional CMS Solutions')

@php
    $breadcrumbs = Breadcrumbs::generate('about');
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
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="animate-slide-up">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-8">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                        </svg>
                        About Our Company
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight">
                        {{ $page->title ?? 'About Us' }}
                    </h1>
                    
                    <!-- Content -->
                    <div class="enhanced-prose max-w-none">
                        {!! $page->content ?? '<p class="text-xl text-text-secondary leading-relaxed">We are a team of passionate developers and designers dedicated to creating exceptional digital experiences. Our mission is to empower businesses with cutting-edge technology and innovative solutions that drive real results.</p>' !!}
                    </div>
                </div>
                
                <div class="animate-slide-up-delay">
                    <div class="relative">
                        <img src="https://images.pexels.com/photos/3184418/pexels-photo-3184418.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                             alt="Our Team" class="rounded-3xl shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-accent/20 to-transparent rounded-3xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Our Core Values
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    What Drives 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Us Forward</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    The principles that guide every decision we make and ensure the quality of our work.
                </p>
            </div>

            <!-- Values Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Innovation -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Innovation
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        We continuously seek new and creative ways to achieve your goals, staying ahead of industry trends and leveraging cutting-edge technology.
                    </p>
                </div>

                <!-- Collaboration -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Collaboration
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        We work with you, not just for you. Your success is our success, and we believe in building lasting partnerships based on trust and mutual respect.
                    </p>
                </div>

                <!-- Results -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Results
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        We focus on measurable metrics and real performance of your investment, delivering tangible outcomes that drive your business forward.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-accent/5 via-surface/50 to-accent/5 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center">
            <div class="relative z-10 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    Ready to Work 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Together?</span>
                </h2>
                <p class="text-xl md:text-2xl text-text-secondary max-w-3xl mx-auto mb-12 leading-relaxed">
                    Let's discuss how we can help transform your digital presence and achieve your business goals.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ url('/contact') }}" class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">Get In Touch</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ url('/features') }}" class="group px-8 py-4 bg-surface text-text-primary border-2 border-border rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-border hover:scale-105 hover:shadow-xl">
                        <span class="flex items-center">
                            View Our Features
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                </div>
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