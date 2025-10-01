@extends('layouts.public')

@section('title', $page->meta_title ?? 'About Us')

@section('content')

    <section class="bg-surface py-16 sm:py-20 border-b border-border">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="prose prose-invert prose-lg max-w-none prose-h2:text-accent prose-h3:text-accent prose-strong:text-text-primary">
                    <h1>{{ $page->title }}</h1>
                    {!! $page->content !!}
                </div>
                <div>
                    <img src="https://images.pexels.com/photos/3184418/pexels-photo-3184418.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" 
                         alt="Our Team" class="rounded-lg shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <section class="bg-background py-16 sm:py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-text-primary">Οι Αξίες Μας</h2>
                <p class="text-text-secondary mt-2 max-w-2xl mx-auto">Οι αρχές που καθοδηγούν κάθε μας βήμα και διασφαλίζουν την ποιότητα της δουλειάς μας.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="bg-surface p-8 rounded-lg border border-border">
                    <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary">Καινοτομία</h3>
                    <p class="mt-1 text-text-secondary">Αναζητούμε συνεχώς νέους και δημιουργικούς τρόπους για να πετυχαίνουμε τους στόχους σας.</p>
                </div>
                <div class="bg-surface p-8 rounded-lg border border-border">
                    <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary">Συνεργασία</h3>
                    <p class="mt-1 text-text-secondary">Δουλεύουμε μαζί σας, όχι απλώς για εσάς. Η επιτυχία σας είναι και δική μας επιτυχία.</p>
                </div>
                <div class="bg-surface p-8 rounded-lg border border-border">
                    <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-text-primary">Αποτελέσματα</h3>
                    <p class="mt-1 text-text-secondary">Εστιάζουμε σε μετρήσιμους δείκτες και πραγματική απόδοση της επένδυσής σας.</p>
                </div>
            </div>
        </div>
    </section>

@endsection