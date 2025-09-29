@extends('layouts.public')

@section('title', 'Επικοινωνία')

@section('content')
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl font-bold text-text-primary mb-2">Επικοινωνήστε Μαζί Μας</h1>
        <p class="text-lg text-text-secondary">Είμαστε εδώ για να βοηθήσουμε. Στείλτε μας την ερώτησή σας παρακάτω.</p>
    </div>

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-center">
        <div class="bg-surface p-8 rounded-lg border border-border">
            <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-text-primary">Το Γραφείο Μας</h3>
            <p class="mt-1 text-text-secondary">Πάτρα, Ελλάδα</p>
        </div>
        <div class="bg-surface p-8 rounded-lg border border-border">
            <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-text-primary">Στείλτε μας Email</h3>
            <a href="mailto:contact@mycms.com" class="mt-1 text-text-secondary hover:text-accent transition block">contact@mycms.com</a>
        </div>
        <div class="bg-surface p-8 rounded-lg border border-border">
            <div class="text-accent inline-block p-3 bg-primary/20 rounded-full mb-4">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-text-primary">Καλέστε μας</h3>
            <p class="mt-1 text-text-secondary">+30 210 123 4567</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-md overflow-hidden border border-border mt-12">
        <div class="p-8">
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-text-secondary">Ονοματεπώνυμο</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-secondary">Διεύθυνση Email</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                    </div>
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-text-secondary">Μήνυμα</label>
                    <textarea name="message" id="message" rows="5" required
                              class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"></textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn-primary">
                        Αποστολή Μηνύματος
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-12 rounded-lg overflow-hidden border border-border">
        <div class="w-full h-96">
            {{-- Paste your Google Maps Iframe code here if you want to keep it --}}
        </div>
    </div>

@endsection