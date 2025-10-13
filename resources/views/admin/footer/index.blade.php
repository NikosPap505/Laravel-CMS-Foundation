@extends('layouts.admin')

@section('title', 'Footer Content')
@section('subtitle', 'Manage footer content and copyright information')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-text-primary">Footer Content</h1>
        </div>

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 md:p-8">
                @if (session('success'))
                    <div class="bg-success/20 text-success p-3 rounded-md mb-6 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.footer.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="footer_about_text" class="block text-sm font-medium text-text-secondary">Footer "About" Text</label>
                            <textarea name="footer_about_text" id="footer_about_text" rows="4" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('footer_about_text', setting('footer_about_text')) }}</textarea>
                            <p class="mt-2 text-xs text-text-secondary">The short text that appears in the first column of the footer.</p>
                        </div>
                        
                        <div>
                            <label for="copyright_text" class="block text-sm font-medium text-text-secondary">Copyright Text</label>
                            <input type="text" name="copyright_text" id="copyright_text" value="{{ old('copyright_text', setting('copyright_text', '© ' . date('Y') . ' ' . config('app.name') . '. All Rights Reserved.')) }}" class="mt-1 block w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                            <p class="mt-2 text-xs text-text-secondary">The copyright text at the very bottom of the page.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 border-t border-border pt-6">
                        <button type="submit" class="btn-primary">Save Footer Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
