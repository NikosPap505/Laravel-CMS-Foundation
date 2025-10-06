<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NWWKG88R');</script>
<!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php 
        $seoItem = $post ?? $page ?? null;
        $title = $seoItem->meta_title ?? $seoItem->title ?? config('app.name', 'Laravel');
        $description = $seoItem->meta_description ?? $seoItem->excerpt ?? '';
        $image = isset($post) && $post->featuredImage ? Storage::url($post->featuredImage->path) : asset('images/default-og.jpg');
        $url = url()->current();
    @endphp
    
    <title>{{ $title }}</title>
    @if($description)
        <meta name="description" content="{{ $description }}">
    @endif
    
    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:type" content="{{ isset($post) ? 'article' : 'website' }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $url }}">
    
    {{-- RSS Feed --}}
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} RSS Feed" href="{{ route('blog.feed') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-text-secondary">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NWWKG88R"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <header class="bg-surface sticky top-0 z-50 shadow-md border-b border-border">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-accent">MyCMS</a>
            <nav>
                <ul class="flex space-x-6">
                    @if(isset($headerMenuItems))
                        @foreach($headerMenuItems as $item)
                            <li>
                                <a href="{{ url($item->link) }}" class="text-text-secondary hover:text-text-primary transition duration-300">{{ $item->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- Flash Messages --}}
    <x-flash-messages />

    <footer class="bg-surface text-text-secondary mt-12 border-t border-border">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-1">
                    <h3 class="text-xl font-bold text-accent mb-4">MyCMS</h3>
                    <p class="text-sm">{{ setting('footer_about_text', 'A modern CMS built with Laravel to bring your ideas to life. Flexible, fast, and powerful.') }}</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        @if(isset($footerMenuItems))
                            @foreach($footerMenuItems as $item)
                                <li>
                                    <a href="{{ url($item->link) }}" class="text-sm hover:text-text-primary transition">{{ $item->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-text-primary mb-4">Subscribe to our Newsletter</h3>
                    @if (session('success'))
                        <div class="text-success mb-2 text-sm">{{ session('success') }}</div>
                    @else
                         <p class="mt-2 text-sm mb-4">Μείνετε ενημερωμένοι με τα τελευταία νέα μας.</p>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex items-center">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your email" required class="w-full bg-background border-border rounded-l-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                        <button type="submit" class="px-4 py-2 bg-primary text-white font-semibold rounded-r-md hover:bg-opacity-90 transition">Subscribe</button>
                    </form>
                    @error('email')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-12 text-center border-t border-border pt-6">
                 <p class="text-sm">{{ setting('copyright_text', '&copy; ' . date('Y') . ' MyCMS. All Rights Reserved.') }}</p>
            </div>
            </div>
        </div>
    </footer>

    {{-- Admin Quick Actions Toolbar --}}
    @include('components.admin-toolbar')
    
    {{-- Stack for additional styles and scripts --}}
    @stack('styles')
    @stack('scripts')
    
    </body>
</html>