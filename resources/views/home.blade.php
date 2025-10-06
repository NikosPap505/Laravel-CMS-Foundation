@extends('layouts.public')

@section('title', 'Digital Solutions Pro - Professional CMS Solutions Provider')

@section('content')
    <!-- Hero Section with Animations -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-background via-surface to-background">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f3f4f6" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-accent/10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-accent/20 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-12 h-12 bg-accent/15 rounded-full animate-pulse"></div>
        
        <!-- Main Content -->
        <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-8 animate-fade-in">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                </svg>
                Professional CMS Solutions Provider
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-5xl md:text-7xl font-bold text-text-primary mb-6 leading-tight animate-slide-up">
                Build Amazing Websites with Our 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70 animate-gradient">
                    Professional CMS
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-text-secondary max-w-4xl mx-auto mb-12 leading-relaxed animate-slide-up-delay">
                Experience the power of enterprise-grade content management. Our Laravel-based CMS delivers 
                <span class="text-accent font-semibold">blazing-fast performance</span>, 
                <span class="text-accent font-semibold">bulletproof security</span>, and 
                <span class="text-accent font-semibold">intuitive design</span>.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-slide-up-delay-2">
                <a href="{{ url('/features') }}" class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                    <span class="relative z-10">View Features</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                <a href="{{ url('/blog') }}" class="group px-8 py-4 bg-surface text-text-primary border-2 border-border rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-border hover:scale-105 hover:shadow-xl">
                    <span class="flex items-center">
                        Explore Our Blog
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16 animate-fade-in-delay">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-accent mb-2 counter" data-target="{{ \App\Models\Post::where('status', 'published')->count() }}">0</div>
                    <div class="text-text-secondary">Published Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-accent mb-2 counter" data-target="{{ \App\Models\Comment::where('status', 'approved')->count() }}">0</div>
                    <div class="text-text-secondary">Active Comments</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-accent mb-2 counter" data-target="{{ \App\Models\Category::count() }}">0</div>
                    <div class="text-text-secondary">Categories</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-accent mb-2 counter" data-target="{{ \App\Models\NewsletterSubscriber::count() }}">0</div>
                    <div class="text-text-secondary">Subscribers</div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Interactive Features Showcase -->
    <section id="features" class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Powerful Features
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    Everything You Need to 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Succeed</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    Our CMS is packed with enterprise-grade features that make content management effortless and powerful.
                </p>
            </div>
            
            <!-- Interactive Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Rich Content Editor
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Create stunning content with our powerful WYSIWYG editor. Support for media, formatting, and real-time collaboration.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        User Management
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Role-based permissions, user authentication, and activity logging for complete control over your content and team.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        SEO Optimized
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Built-in SEO tools, meta management, and search engine optimization features for maximum visibility and traffic.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Media Management
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Drag-and-drop file uploads, automatic image optimization, and organized media library for all your assets.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Analytics & Insights
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Track performance, monitor user engagement, and get valuable insights to optimize your content strategy.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="group feature-card bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Security First
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Enterprise-grade security with role-based access, data encryption, and regular security updates.
                    </p>
                    <div class="flex items-center text-accent font-semibold group-hover:translate-x-2 transition-transform duration-300">
                        Learn More
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Before/After Comparison Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-background to-surface/50 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Transformation Results
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    See the 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Difference</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    Discover how our CMS transforms your content management from chaotic to professional.
                </p>
            </div>
            
            <!-- Comparison Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
                <!-- Before -->
                <div class="group">
                    <div class="bg-surface/30 backdrop-blur-sm border border-border/50 rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-4 right-4">
                            <span class="bg-text-secondary/20 text-text-secondary text-xs font-bold px-3 py-1 rounded-full">BEFORE</span>
                        </div>
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-text-primary mb-4">Traditional Content Management</h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-text-secondary">
                                    <svg class="w-5 h-5 mr-3 text-text-secondary/60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Manual HTML coding for every update
                                </div>
                                <div class="flex items-center text-text-secondary">
                                    <svg class="w-5 h-5 mr-3 text-text-secondary/60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    No user management or permissions
                                </div>
                                <div class="flex items-center text-text-secondary">
                                    <svg class="w-5 h-5 mr-3 text-text-secondary/60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Poor SEO and no meta management
                                </div>
                                <div class="flex items-center text-text-secondary">
                                    <svg class="w-5 h-5 mr-3 text-text-secondary/60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    No analytics or performance tracking
                                </div>
                                <div class="flex items-center text-text-secondary">
                                    <svg class="w-5 h-5 mr-3 text-text-secondary/60" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Security vulnerabilities and risks
                                </div>
                            </div>
                        </div>
                        <div class="bg-surface/50 rounded-lg p-4 border border-border/30">
                            <div class="text-sm text-text-primary font-semibold mb-2">Result:</div>
                            <div class="text-text-secondary">Hours of manual work, poor user experience, and security risks</div>
                        </div>
                    </div>
                </div>
                
                <!-- After -->
                <div class="group">
                    <div class="bg-surface/50 backdrop-blur-sm border border-accent/30 rounded-2xl p-8 relative overflow-hidden shadow-lg">
                        <div class="absolute top-4 right-4">
                            <span class="bg-accent text-white text-xs font-bold px-3 py-1 rounded-full">AFTER</span>
                        </div>
                        <div class="mb-6">
                            <h3 class="text-2xl font-bold text-text-primary mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                </svg>
                                With Our Professional CMS
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-text-primary bg-surface/30 rounded-lg p-3">
                                    <svg class="w-5 h-5 mr-3 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">WYSIWYG editor with real-time preview</span>
                                </div>
                                <div class="flex items-center text-text-primary bg-surface/30 rounded-lg p-3">
                                    <svg class="w-5 h-5 mr-3 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Role-based permissions and team collaboration</span>
                                </div>
                                <div class="flex items-center text-text-primary bg-surface/30 rounded-lg p-3">
                                    <svg class="w-5 h-5 mr-3 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Built-in SEO tools and meta management</span>
                                </div>
                                <div class="flex items-center text-text-primary bg-surface/30 rounded-lg p-3">
                                    <svg class="w-5 h-5 mr-3 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Comprehensive analytics and insights</span>
                                </div>
                                <div class="flex items-center text-text-primary bg-surface/30 rounded-lg p-3">
                                    <svg class="w-5 h-5 mr-3 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Enterprise-grade security and protection</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-accent/10 rounded-lg p-4 border border-accent/20">
                            <div class="text-sm text-accent font-bold mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Result:
                            </div>
                            <div class="text-text-primary font-semibold text-lg">Minutes of work, professional results, and peace of mind</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Benefits Summary -->
            <div class="bg-surface/50 backdrop-blur-sm rounded-2xl border border-border/50 p-8 text-center">
                <h3 class="text-2xl font-bold text-text-primary mb-4">The Transformation Impact</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-accent mb-2">90%</div>
                        <div class="text-text-secondary">Time Saved</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-accent mb-2">300%</div>
                        <div class="text-text-secondary">Faster Updates</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-accent mb-2">100%</div>
                        <div class="text-text-secondary">Security Confidence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Posts Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                    </svg>
                    Latest Content
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    From Our 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Blog</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    Discover insights, tutorials, and industry trends powered by our CMS.
                </p>
            </div>
            
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
                            <p class="text-text-secondary mb-6 leading-relaxed">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>
                            
                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-sm text-text-secondary">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $post->published_at->format('M j, Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $post->view_count ?? 0 }} views
                                    </span>
                                </div>
                                <a href="{{ route('blog.show', $post) }}" class="text-accent hover:text-accent/80 font-semibold transition-colors duration-300">
                                    Read More →
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="w-24 h-24 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-text-primary mb-4">No Posts Yet</h3>
                        <p class="text-text-secondary mb-8">Check back soon for amazing content!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- CTA -->
            <div class="text-center mt-16">
                <a href="{{ route('blog.index') }}" class="group relative inline-flex items-center px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                    <span class="relative z-10">Explore Live Blog</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-accent/5 via-surface/50 to-accent/5 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center">
            <!-- Floating Background Elements -->
            <div class="absolute top-10 left-10 w-32 h-32 bg-accent/10 rounded-full animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-24 h-24 bg-accent/15 rounded-full animate-bounce"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-accent/20 rounded-full animate-pulse"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-8">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Ready to Transform Your Website?
                </div>
                
                <!-- Heading -->
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-8">
                    Let's Build Something 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Amazing</span>
                </h2>
                
                <!-- Description -->
                <p class="text-xl text-text-secondary mb-12 max-w-3xl mx-auto leading-relaxed">
                    Join thousands of businesses who have transformed their online presence with our professional CMS. 
                    Get started today and experience the difference.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-12">
                    <a href="{{ url('/contact') }}" class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">Get Started Today</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ url('/features') }}" class="group px-8 py-4 bg-surface text-text-primary border-2 border-border rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-border hover:scale-105 hover:shadow-xl">
                        <span class="flex items-center">
                            View All Features
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">1000+</div>
                        <div class="text-text-secondary">Happy Clients</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">99.9%</div>
                        <div class="text-text-secondary">Uptime</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">24/7</div>
                        <div class="text-text-secondary">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
<script>
    // Counter Animation - runs only once per page load
    let countersAnimated = false;
    
    function animateCounters() {
        if (countersAnimated) return; // Prevent multiple animations
        
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current);
            }, 16);
        });
        
        countersAnimated = true; // Mark as animated
    }
    
    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                
                // Animate counters when they come into view (only once)
                if (entry.target.classList.contains('counter') && !countersAnimated) {
                    animateCounters();
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Separate observer for counters to ensure they only animate once
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !countersAnimated) {
                animateCounters();
                counterObserver.unobserve(entry.target); // Stop observing after animation
            }
        });
    }, {
        threshold: 0.5
    });
    
    // Observe elements for animation
    document.addEventListener('DOMContentLoaded', () => {
        const elementsToAnimate = document.querySelectorAll('.feature-card, .demo-card, [class*="animate-"]');
        elementsToAnimate.forEach(el => observer.observe(el));
        
        // Observe counters separately to ensure one-time animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => counterObserver.observe(counter));
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endpush

@endsection
