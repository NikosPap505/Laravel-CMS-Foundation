@extends('layouts.public')

@section('title', $page->meta_title ?? 'Οι Υπηρεσίες μας')

@php
    $breadcrumbs = Breadcrumbs::generate('services');
@endphp

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
                <div class="enhanced-prose max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Enhanced Prose Styling for Better Readability */
    .enhanced-prose {
        color: inherit;
        font-size: 18px;
        line-height: 1.8;
        max-width: none;
    }
    
    /* Typography - High specificity to override TinyMCE */
    .enhanced-prose h1,
    .enhanced-prose h2,
    .enhanced-prose h3,
    .enhanced-prose h4,
    .enhanced-prose h5,
    .enhanced-prose h6 {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
        margin: 2rem 0 1rem 0 !important;
        line-height: 1.3 !important;
    }
    
    .enhanced-prose h1 { font-size: 2.5rem !important; }
    .enhanced-prose h2 { 
        font-size: 2rem !important; 
        border-bottom: 2px solid var(--accent) !important;
        padding-bottom: 0.5rem !important;
    }
    .enhanced-prose h3 { font-size: 1.5rem !important; }
    .enhanced-prose h4 { font-size: 1.25rem !important; }
    .enhanced-prose h5, .enhanced-prose h6 { font-size: 1.125rem !important; }
    
    /* Paragraphs */
    .enhanced-prose p {
        color: var(--text-secondary) !important;
        line-height: 1.8 !important;
        margin: 1.5rem 0 !important;
        font-size: 18px !important;
    }
    
    /* Links */
    .enhanced-prose a {
        color: var(--accent) !important;
        text-decoration: none !important;
        border-bottom: 1px solid transparent !important;
        transition: all 0.3s ease !important;
    }
    
    .enhanced-prose a:hover {
        border-bottom-color: var(--accent) !important;
    }
    
    /* Lists */
    .enhanced-prose ul, .enhanced-prose ol {
        margin: 1.5rem 0 !important;
        padding-left: 2rem !important;
    }
    
    .enhanced-prose li {
        margin: 0.75rem 0 !important;
        line-height: 1.7 !important;
        color: var(--text-secondary) !important;
    }
    
    /* Code */
    .enhanced-prose code {
        background: var(--surface) !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.375rem !important;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
        color: var(--accent) !important;
        border: 1px solid var(--border) !important;
    }
    
    .enhanced-prose pre {
        background: #1a1a1a !important;
        border-radius: 0.75rem !important;
        padding: 1.5rem !important;
        margin: 2rem 0 !important;
        border: 1px solid var(--border) !important;
        overflow-x: auto !important;
    }
    
    .enhanced-prose pre code {
        background: none !important;
        padding: 0 !important;
        border: none !important;
        color: #e2e8f0 !important;
    }
    
    /* Images */
    .enhanced-prose img {
        border-radius: 0.75rem !important;
        margin: 2rem 0 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        max-width: 100% !important;
        height: auto !important;
    }
    
    /* Tables */
    .enhanced-prose table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 2rem 0 !important;
        background: var(--surface) !important;
        border-radius: 0.75rem !important;
        overflow: hidden !important;
    }
    
    .enhanced-prose th, .enhanced-prose td {
        padding: 1rem !important;
        border-bottom: 1px solid var(--border) !important;
    }
    
    .enhanced-prose th {
        background: var(--accent) !important;
        color: white !important;
        font-weight: 600 !important;
    }
    
    /* Blockquotes */
    .enhanced-prose blockquote {
        border-left: 4px solid var(--accent) !important;
        background: var(--surface) !important;
        padding: 2rem !important;
        margin: 2rem 0 !important;
        border-radius: 0.75rem !important;
        font-style: italic !important;
    }
    
    .enhanced-prose blockquote p {
        margin: 0 !important;
        color: var(--text-primary) !important;
    }
    
    /* Strong and Emphasis */
    .enhanced-prose strong {
        color: var(--text-primary) !important;
        font-weight: 700 !important;
    }
    
    .enhanced-prose em {
        color: var(--text-primary) !important;
        font-style: italic !important;
    }
</style>
@endpush