@extends('layouts.public')

{{-- The 'page' variable is passed from the route for SEO data --}}
@section('title', $page->meta_title ?? 'Welcome')

@section('content')
    <section class="text-center py-12 md:py-20">
        <h1 class="text-4xl md:text-6xl font-bold text-text-primary mb-4 leading-tight">
            Μετατρέπουμε τις Ιδέες σε <span class="text-accent">Ψηφιακή Επιτυχία</span>
        </h1>
        <p class="text-lg md:text-xl text-text-secondary max-w-3xl mx-auto mb-8">
            Είμαστε ο στρατηγικός σας συνεργάτης στον κόσμο του digital marketing, προσφέροντας καινοτόμες λύσεις που φέρνουν πραγματικά, μετρήσιμα αποτελέσματα.
        </p>
        <div class="flex justify-center space-x-4">
            <a href="{{ url('/services') }}" class="btn-primary">Οι Υπηρεσίες μας</a>
            <a href="{{ url('/contact') }}" class="inline-flex items-center px-4 py-2 bg-surface text-text-secondary border border-border rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-border hover:text-text-primary transition">
                Επικοινωνία
            </a>
        </div>
    </section>

    <section class="py-12 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-surface p-8 rounded-lg border border-border">
                <div class="text-accent mb-4">
                    <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Search Engine Optimization</h3>
                <p class="text-text-secondary">Κατακτήστε την κορυφή των αποτελεσμάτων της Google και αυξήστε την οργανική σας επισκεψιμότητα.</p>
            </div>
            <div class="bg-surface p-8 rounded-lg border border-border">
                <div class="text-accent mb-4">
                    <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Modern Web Design</h3>
                <p class="text-text-secondary">Σχεδιάζουμε μοντέρνα και γρήγορα websites που μετατρέπουν τους επισκέπτες σε πελάτες.</p>
            </div>
            <div class="bg-surface p-8 rounded-lg border border-border">
                <div class="text-accent mb-4">
                    <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-text-primary mb-2">Social Media Strategy</h3>
                <p class="text-text-secondary">Χτίζουμε μια ισχυρή σχέση με το κοινό σας και αυξάνουμε την αλληλεπίδραση με το brand σας.</p>
            </div>
        </div>
    </section>

    <section class="bg-surface text-center py-12 md:py-20 rounded-lg border border-border">
        <h2 class="text-3xl font-bold text-text-primary mb-4">Είστε έτοιμοι να ξεκινήσουμε;</h2>
        <p class="text-text-secondary max-w-2xl mx-auto mb-8">Επικοινωνήστε μαζί μας σήμερα για να συζητήσουμε τις ανάγκες σας και να σχεδιάσουμε μαζί την επόμενη επιτυχία σας.</p>
        <a href="{{ url('/contact') }}" class="btn-primary">Επικοινωνήστε μαζί μας</a>
    </section>
@endsection