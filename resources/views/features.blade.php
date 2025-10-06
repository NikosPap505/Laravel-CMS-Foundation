@extends('layouts.public')

@section('title', 'Features - Professional CMS Solutions')

@section('content')
    <!-- Hero Section -->
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
                Complete Feature Set
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-5xl md:text-7xl font-bold text-text-primary mb-6 leading-tight animate-slide-up">
                Everything You Need to 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70 animate-gradient">
                    Succeed
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-text-secondary max-w-4xl mx-auto mb-12 leading-relaxed animate-slide-up-delay">
                Our CMS is packed with enterprise-grade features that make content management effortless, 
                secure, and powerful. Built for modern businesses that demand excellence.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-slide-up-delay-2">
                <a href="#features" class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                    <span class="relative z-10">Explore Features</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
                <a href="{{ url('/contact') }}" class="group px-8 py-4 bg-surface text-text-primary border-2 border-border rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-border hover:scale-105 hover:shadow-xl">
                    <span class="flex items-center">
                        Get Started
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Core Features
                </div>
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    Powerful Tools for 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Modern Businesses</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-3xl mx-auto">
                    Every feature is designed with your success in mind. From content creation to analytics, 
                    we've got you covered.
                </p>
            </div>
            
            <!-- Feature Grid -->
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
                        Create stunning content with our powerful WYSIWYG editor. Support for media, formatting, 
                        and real-time collaboration with your team.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            WYSIWYG editing with live preview
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Media library integration
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Version control and history
                        </li>
                    </ul>
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
                        Role-based permissions, user authentication, and activity logging for complete control 
                        over your content and team collaboration.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Role-based access control
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Activity logging and audit trails
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Team collaboration tools
                        </li>
                    </ul>
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
                        SEO Optimization
                    </h3>
                    <p class="text-text-secondary mb-6 leading-relaxed">
                        Built-in SEO tools, meta management, and search engine optimization features for 
                        maximum visibility and organic traffic growth.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Meta title and description management
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            URL structure optimization
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            XML sitemap generation
                        </li>
                    </ul>
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
                        Drag-and-drop file uploads, automatic image optimization, and organized media library 
                        for all your digital assets.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Drag-and-drop file uploads
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Automatic image optimization
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Organized media library
                        </li>
                    </ul>
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
                        Track performance, monitor user engagement, and get valuable insights to optimize 
                        your content strategy and grow your audience.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Real-time performance metrics
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            User engagement tracking
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Content performance reports
                        </li>
                    </ul>
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
                        Enterprise-grade security with role-based access, data encryption, and regular 
                        security updates to protect your content and users.
                    </p>
                    <ul class="text-sm text-text-secondary space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Role-based access control
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Data encryption and protection
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-accent mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Regular security updates
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-accent/5 via-surface/50 to-accent/5 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center">
            <div class="relative z-10 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-6xl font-bold text-text-primary mb-6">
                    Ready to Experience These 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Features?</span>
                </h2>
                <p class="text-xl md:text-2xl text-text-secondary max-w-3xl mx-auto mb-12 leading-relaxed">
                    Join hundreds of businesses who trust our CMS to power their digital presence. 
                    Get started today and see the difference professional content management makes.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ url('/contact') }}" class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">Get Started Today</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ url('/') }}" class="group px-8 py-4 bg-surface text-text-primary border-2 border-border rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-border hover:scale-105 hover:shadow-xl">
                        <span class="flex items-center">
                            Back to Home
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
    
    /* Feature Card Hover Effects */
    .feature-card {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card:hover {
        transform: translateY(-8px) scale(1.02);
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
