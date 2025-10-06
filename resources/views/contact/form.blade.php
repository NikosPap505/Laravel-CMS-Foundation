@extends('layouts.public')

@section('title', 'Contact Us - Professional CMS Solutions')

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
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-8 animate-fade-in">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8.5-3a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM9.5 12a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" clip-rule="evenodd"></path>
                </svg>
                Get In Touch
            </div>
            
            <!-- Main Heading -->
            <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-6 leading-tight animate-slide-up">
                Let's Start a 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70 animate-gradient">
                    Conversation
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-text-secondary max-w-3xl mx-auto mb-12 leading-relaxed animate-slide-up-delay">
                Ready to transform your digital presence? We're here to help you succeed. 
                Send us a message and let's discuss how we can bring your vision to life.
            </p>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Office -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Our Office
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        Patras, Greece<br>
                        <span class="text-sm text-text-secondary/80">Visit us for a coffee and discussion</span>
                    </p>
                </div>

                <!-- Email -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Email Us
                    </h3>
                    <a href="mailto:contact@mycms.com" class="text-accent hover:text-accent/80 transition-colors duration-300 font-semibold">
                        contact@mycms.com
                    </a>
                    <p class="text-sm text-text-secondary/80 mt-2">We respond within 24 hours</p>
                </div>

                <!-- Phone -->
                <div class="group bg-surface/50 backdrop-blur-sm p-8 rounded-2xl border border-border/50 hover:border-accent/30 transition-all duration-500 hover:shadow-2xl hover:shadow-accent/10 hover:-translate-y-2 text-center">
                    <div class="relative mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/70 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent/20 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-text-primary mb-4 group-hover:text-accent transition-colors duration-300">
                        Call Us
                    </h3>
                    <a href="tel:+302101234567" class="text-accent hover:text-accent/80 transition-colors duration-300 font-semibold">
                        +30 210 123 4567
                    </a>
                    <p class="text-sm text-text-secondary/80 mt-2">Mon-Fri 9AM-6PM EET</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-20 md:py-32 bg-gradient-to-br from-surface/30 to-background relative">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Form Header -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                        Send Us a 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Message</span>
                    </h2>
                    <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                        Tell us about your project and we'll get back to you with a personalized solution.
                    </p>
                </div>

                <!-- Contact Form -->
                <div class="bg-surface/50 backdrop-blur-sm rounded-3xl border border-border/50 p-8 md:p-12 shadow-xl">
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-text-primary mb-3">
                                    Full Name *
                                </label>
                                <input type="text" name="name" id="name" required
                                       class="w-full px-4 py-3 bg-background/50 border border-border/50 rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300"
                                       placeholder="Your full name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-text-primary mb-3">
                                    Email Address *
                                </label>
                                <input type="email" name="email" id="email" required
                                       class="w-full px-4 py-3 bg-background/50 border border-border/50 rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300"
                                       placeholder="your@email.com">
                            </div>
                        </div>
                        
                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-text-primary mb-3">
                                Message *
                            </label>
                            <textarea name="message" id="message" rows="6" required
                                      class="w-full px-4 py-3 bg-background/50 border border-border/50 rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300 resize-none"
                                      placeholder="Tell us about your project, goals, and how we can help..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" 
                                    class="group relative px-8 py-4 bg-accent text-white rounded-lg font-semibold text-lg transition-all duration-300 hover:bg-accent/90 hover:scale-105 hover:shadow-2xl">
                                <span class="relative z-10 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Send Message
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-accent to-accent/70 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 md:py-32 relative">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                    Find Us on the 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent/70">Map</span>
                </h2>
                <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                    Visit our office in Patras, Greece. We'd love to meet you in person!
                </p>
            </div>
            
            <div class="bg-surface/50 backdrop-blur-sm rounded-3xl border border-border/50 overflow-hidden shadow-xl">
                <div class="w-full h-96 bg-gradient-to-br from-accent/10 to-accent/5 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-accent mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-text-secondary font-semibold">Interactive Map Coming Soon</p>
                        <p class="text-text-secondary/80 text-sm">Patras, Greece</p>
                    </div>
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
    
    /* Form Focus Effects */
    input:focus, textarea:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush