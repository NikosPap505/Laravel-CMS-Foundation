@extends('layouts.public')

@section('title', 'Thank You')

@section('content')
    <div class="bg-surface rounded-lg shadow-md overflow-hidden border border-border max-w-2xl mx-auto">
        <div class="p-8 md:p-12 text-center">

            <div class="w-16 h-16 bg-success/20 rounded-full p-3 flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-text-primary mt-6 mb-2">Σας ευχαριστούμε!</h1>

            <p class="text-text-secondary mb-8">
                Το μήνυμά σας έχει αποσταλεί με επιτυχία. Θα επικοινωνήσουμε μαζί σας το συντομότερο δυνατό.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="btn-primary">
                    Επιστροφή στην Αρχική
                </a>
                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-4 py-2 bg-surface text-text-secondary border border-border rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-border hover:text-text-primary transition">
                    Δείτε το Blog μας
                </a>
            </div>

        </div>
    </div>
@endsection