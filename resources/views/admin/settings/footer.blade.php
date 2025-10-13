@extends('layouts.admin')

@section('title', 'Footer Settings')
@section('subtitle', 'Footer content and links configuration')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center text-sm font-medium text-text-secondary hover:text-accent">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Settings
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Footer Settings</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="bg-success/20 text-success p-4 rounded-md mb-6 text-sm border border-success/30">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-surface overflow-hidden shadow-lg sm:rounded-lg border border-border">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.settings.store.footer') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- Footer About Section --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">About Section</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="footer_about_text" class="block text-sm font-medium text-text-secondary mb-2">Footer "About" Text</label>
                                    <textarea name="footer_about_text" id="footer_about_text" rows="4" 
                                              class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">{{ old('footer_about_text', setting('footer.footer_about_text', setting('footer_about_text'))) }}</textarea>
                                    <p class="mt-2 text-xs text-text-secondary">The short text that appears in the first column of the footer.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Copyright --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Copyright</h3>
                            <div>
                                <label for="copyright_text" class="block text-sm font-medium text-text-secondary mb-2">Copyright Text</label>
                                <input type="text" name="copyright_text" id="copyright_text" 
                                       value="{{ old('copyright_text', setting('footer.copyright_text', setting('copyright_text', '© ' . date('Y') . ' ' . config('app.name') . '. All Rights Reserved.'))) }}" 
                                       class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                <p class="mt-2 text-xs text-text-secondary">The copyright text at the very bottom of the page.</p>
                            </div>
                        </div>

                        {{-- Social Media Links --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Social Media Links</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="social_facebook" class="block text-sm font-medium text-text-secondary mb-2">Facebook URL</label>
                                    <input type="url" name="social_facebook" id="social_facebook" 
                                           value="{{ old('social_facebook', setting('footer.social_facebook')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="https://facebook.com/yourpage">
                                </div>

                                <div>
                                    <label for="social_twitter" class="block text-sm font-medium text-text-secondary mb-2">Twitter URL</label>
                                    <input type="url" name="social_twitter" id="social_twitter" 
                                           value="{{ old('social_twitter', setting('footer.social_twitter')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="https://twitter.com/yourhandle">
                                </div>

                                <div>
                                    <label for="social_instagram" class="block text-sm font-medium text-text-secondary mb-2">Instagram URL</label>
                                    <input type="url" name="social_instagram" id="social_instagram" 
                                           value="{{ old('social_instagram', setting('footer.social_instagram')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="https://instagram.com/yourhandle">
                                </div>

                                <div>
                                    <label for="social_linkedin" class="block text-sm font-medium text-text-secondary mb-2">LinkedIn URL</label>
                                    <input type="url" name="social_linkedin" id="social_linkedin" 
                                           value="{{ old('social_linkedin', setting('footer.social_linkedin')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="https://linkedin.com/company/yourcompany">
                                </div>

                                <div>
                                    <label for="social_youtube" class="block text-sm font-medium text-text-secondary mb-2">YouTube URL</label>
                                    <input type="url" name="social_youtube" id="social_youtube" 
                                           value="{{ old('social_youtube', setting('footer.social_youtube')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="https://youtube.com/channel/yourchannel">
                                </div>
                            </div>
                        </div>

                        {{-- Footer Links --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Footer Links</h3>
                            <div id="footer-links-container">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-text-primary">Quick Links</h4>
                                        <button type="button" onclick="addFooterLink()" class="text-sm text-accent hover:underline">
                                            + Add Link
                                        </button>
                                    </div>
                                    
                                    <div id="footer-links-list">
                                        @php
                                            $footerLinks = json_decode(setting('footer.footer_links'), true) ?? [];
                                            if (empty($footerLinks)) {
                                                $footerLinks = [
                                                    ['title' => 'About Us', 'url' => '/about'],
                                                    ['title' => 'Contact', 'url' => '/contact'],
                                                    ['title' => 'Privacy Policy', 'url' => '/privacy'],
                                                    ['title' => 'Terms of Service', 'url' => '/terms']
                                                ];
                                            }
                                        @endphp
                                        
                                        @foreach($footerLinks as $index => $link)
                                            <div class="footer-link-item flex items-center space-x-3 p-3 border border-border rounded-lg">
                                                <input type="text" name="footer_links[{{ $index }}][title]" 
                                                       value="{{ $link['title'] ?? '' }}" 
                                                       placeholder="Link Title" 
                                                       class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                                <input type="url" name="footer_links[{{ $index }}][url]" 
                                                       value="{{ $link['url'] ?? '' }}" 
                                                       placeholder="/page-url" 
                                                       class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                                <button type="button" onclick="removeFooterLink(this)" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Scripts --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Footer Scripts</h3>
                            <div>
                                <label for="footer_scripts" class="block text-sm font-medium text-text-secondary mb-2">Custom Footer Scripts</label>
                                <textarea name="footer_scripts" id="footer_scripts" rows="6" 
                                          class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent font-mono text-sm"
                                          placeholder="<!-- Custom scripts will be added here -->
<script>
    // Your custom JavaScript
</script>">{{ old('footer_scripts', setting('footer.footer_scripts')) }}</textarea>
                                <p class="mt-1 text-xs text-text-secondary">Custom JavaScript code that will be added before the closing &lt;/body&gt; tag</p>
                            </div>
                        </div>

                        {{-- Footer Preview --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Footer Preview</h3>
                            <div class="bg-background border border-border rounded-lg p-6">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    {{-- About Column --}}
                                    <div>
                                        <h4 class="font-semibold text-text-primary mb-2">About</h4>
                                        <p class="text-sm text-text-secondary">{{ setting('footer.footer_about_text', setting('footer_about_text', 'Your site description goes here.')) }}</p>
                                    </div>
                                    
                                    {{-- Links Column --}}
                                    <div>
                                        <h4 class="font-semibold text-text-primary mb-2">Quick Links</h4>
                                        <div class="space-y-1">
                                            @foreach($footerLinks as $link)
                                                <a href="{{ $link['url'] ?? '#' }}" class="block text-sm text-text-secondary hover:text-accent">{{ $link['title'] ?? 'Link' }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    {{-- Social Column --}}
                                    <div>
                                        <h4 class="font-semibold text-text-primary mb-2">Follow Us</h4>
                                        <div class="flex space-x-2">
                                            @if(setting('footer.social_facebook'))
                                                <a href="{{ setting('footer.social_facebook') }}" class="text-text-secondary hover:text-accent">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(setting('footer.social_twitter'))
                                                <a href="{{ setting('footer.social_twitter') }}" class="text-text-secondary hover:text-accent">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Contact Column --}}
                                    <div>
                                        <h4 class="font-semibold text-text-primary mb-2">Contact</h4>
                                        <p class="text-sm text-text-secondary">{{ setting('footer.copyright_text', setting('copyright_text', '© ' . date('Y') . ' ' . config('app.name') . '. All Rights Reserved.')) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-border pt-6">
                        <a href="{{ route('admin.settings.index') }}" class="mr-4 px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Save Footer Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let linkIndex = {{ count($footerLinks) }};

function addFooterLink() {
    const container = document.getElementById('footer-links-list');
    const newLink = document.createElement('div');
    newLink.className = 'footer-link-item flex items-center space-x-3 p-3 border border-border rounded-lg';
    newLink.innerHTML = `
        <input type="text" name="footer_links[${linkIndex}][title]" placeholder="Link Title" 
               class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
        <input type="url" name="footer_links[${linkIndex}][url]" placeholder="/page-url" 
               class="flex-1 bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
        <button type="button" onclick="removeFooterLink(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    container.appendChild(newLink);
    linkIndex++;
}

function removeFooterLink(button) {
    button.closest('.footer-link-item').remove();
}
</script>
@endsection
