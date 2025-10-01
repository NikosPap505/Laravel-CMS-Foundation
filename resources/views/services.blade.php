@extends('layouts.public')

@section('title', $page->meta_title ?? 'Οι Υπηρεσίες μας')

@section('content')
    <section class="text-center py-12">
        <h1 class="text-4xl md:text-5xl font-bold text-text-primary mb-2">
            {{ $page->title }}
        </h1>
        <p class="text-lg text-text-secondary max-w-2xl mx-auto">
            Ανακαλύψτε πώς μπορούμε να βοηθήσουμε την επιχείρησή σας να αναπτυχθεί με την πλήρη γκάμα των εξειδικευμένων ψηφιακών υπηρεσιών μας.
        </p>
    </section>

    <section class="py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="bg-surface p-8 rounded-lg border border-border text-center transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <div class="text-accent inline-block p-4 bg-primary/20 rounded-full mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-text-primary mb-2">Βελτιστοποίηση SEO</h3>
                <p class="text-text-secondary">Βελτιστοποιούμε το site σας για να κατακτήσετε υψηλότερες θέσεις στη Google, προσελκύοντας περισσότερη οργανική επισκεψιμότητα.</p>
            </div>

            <div class="bg-surface p-8 rounded-lg border border-border text-center transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <div class="text-accent inline-block p-4 bg-primary/20 rounded-full mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-text-primary mb-2">Web Design & Development</h3>
                <p class="text-text-secondary">Σχεδιάζουμε και κατασκευάζουμε μοντέρνα, γρήγορα και φιλικά προς τις κινητές συσκευές websites που εντυπωσιάζουν.</p>
            </div>

            <div class="bg-surface p-8 rounded-lg border border-border text-center transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <div class="text-accent inline-block p-4 bg-primary/20 rounded-full mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-text-primary mb-2">Social Media Marketing</h3>
                <p class="text-text-secondary">Δημιουργούμε και διαχειριζόμαστε στοχευμένες καμπάνιες που χτίζουν το brand σας και αυξάνουν την αλληλεπίδραση.</p>
            </div>

        </div>
    </section>

    <section class="py-12">
        <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-md overflow-hidden border border-border">
            <div class="p-8 md:p-10 lg:p-12">
                <div class="prose prose-invert prose-lg max-w-none prose-a:text-accent">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
@endsection