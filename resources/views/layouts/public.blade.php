<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        // Ελέγχουμε αν στη σελίδα έχει περαστεί ένα Post ή ένα Page object.
        $seoItem = $post ?? $page ?? null;
    @endphp

    {{-- Δυναμικός Τίτλος:
         - Αν υπάρχει meta_title, χρησιμοποίησέ το.
         - Αλλιώς, χρησιμοποίησε τον κανονικό τίτλο.
         - Αλλιώς, χρησιμοποίησε το όνομα της εφαρμογής.
    --}}
    <title>{{ $seoItem->meta_title ?? $seoItem->title ?? config('app.name', 'Laravel') }}</title>

    {{-- Δυναμική Περιγραφή:
         - Αν υπάρχει meta_description, εμφάνισε το tag.
    --}}
    @if(isset($seoItem->meta_description))
        <meta name="description" content="{{ $seoItem->meta_description }}">
    @endif


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-text-secondary">

    <header class="bg-surface shadow-md">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-accent">
                MyCMS
            </a>
            <nav>
                <ul class="flex space-x-6">
                    @if(isset($menuItems))
                        @foreach($menuItems as $item)
                            <li>
                                <a href="{{ url($item->link) }}" class="text-text-secondary hover:text-text-primary transition duration-300">{{ $item->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-surface text-text-secondary mt-12 border-t border-border">
        <div class="container mx-auto px-4 py-6 text-center">
            <p>&copy; {{ date('Y') }} MyCMS. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>