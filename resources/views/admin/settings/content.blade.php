@extends('layouts.admin')

@section('title', 'Content Settings')
@section('subtitle', 'Content management and display options')

@php
    // Determine current comment approval mode
    $autoApprove = setting('content.auto_approve_comments', false);
    $moderation = setting('content.comments_moderation', true);
    
    // If auto-approve is enabled, use auto mode; otherwise use manual mode
    $currentApprovalMode = $autoApprove ? 'auto' : 'manual';
@endphp

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
                        <span class="ml-1 text-sm font-medium text-text-primary md:ml-2">Content Settings</span>
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
                <form action="{{ route('admin.settings.store.content') }}" method="POST">
                    @csrf
                    <div class="space-y-8">
                        {{-- Display Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Display Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="posts_per_page" class="block text-sm font-medium text-text-secondary mb-2">Posts Per Page</label>
                                    <input type="number" name="posts_per_page" id="posts_per_page" 
                                           value="{{ old('posts_per_page', setting('content.posts_per_page', 10)) }}" 
                                           min="1" max="100"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Number of posts displayed per page in blog listings</p>
                                </div>

                                <div>
                                    <label for="default_post_template" class="block text-sm font-medium text-text-secondary mb-2">Default Post Template</label>
                                    <select name="default_post_template" id="default_post_template" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="default" {{ old('default_post_template', setting('content.default_post_template', 'default')) == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="featured" {{ old('default_post_template', setting('content.default_post_template', 'default')) == 'featured' ? 'selected' : '' }}>Featured</option>
                                        <option value="minimal" {{ old('default_post_template', setting('content.default_post_template', 'default')) == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                        <option value="card" {{ old('default_post_template', setting('content.default_post_template', 'default')) == 'card' ? 'selected' : '' }}>Card</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Default template for displaying posts</p>
                                </div>

                                <div>
                                    <label for="default_page_template" class="block text-sm font-medium text-text-secondary mb-2">Default Page Template</label>
                                    <select name="default_page_template" id="default_page_template" 
                                            class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                        <option value="default" {{ old('default_page_template', setting('content.default_page_template', 'default')) == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="full-width" {{ old('default_page_template', setting('content.default_page_template', 'default')) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                                        <option value="sidebar" {{ old('default_page_template', setting('content.default_page_template', 'default')) == 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                                        <option value="landing" {{ old('default_page_template', setting('content.default_page_template', 'default')) == 'landing' ? 'selected' : '' }}>Landing Page</option>
                                    </select>
                                    <p class="mt-1 text-xs text-text-secondary">Default template for displaying pages</p>
                                </div>
                            </div>
                        </div>

                        {{-- Comment Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Comment Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="comments_enabled" id="comments_enabled" value="1" 
                                           {{ old('comments_enabled', setting('content.comments_enabled', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="comments_enabled" class="ml-2 block text-sm text-text-primary">
                                        Enable Comments
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Allow visitors to comment on posts</p>

                                <div class="flex items-center">
                                    <input type="checkbox" name="comments_guest_allowed" id="comments_guest_allowed" value="1" 
                                           {{ old('comments_guest_allowed', setting('content.comments_guest_allowed', true)) ? 'checked' : '' }}
                                           class="h-4 w-4 text-accent focus:ring-accent border-border rounded">
                                    <label for="comments_guest_allowed" class="ml-2 block text-sm text-text-primary">
                                        Allow Guest Comments
                                    </label>
                                </div>
                                <p class="text-xs text-text-secondary ml-6">Allow non-registered users to post comments</p>

                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="comment_approval" value="manual" 
                                               {{ old('comment_approval', $currentApprovalMode) == 'manual' ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border">
                                        <span class="ml-2 text-sm text-text-primary">Require manual approval</span>
                                    </label>
                                    <p class="text-xs text-text-secondary ml-6">All comments must be approved before appearing</p>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" name="comment_approval" value="auto" 
                                               {{ old('comment_approval', $currentApprovalMode) == 'auto' ? 'checked' : '' }}
                                               class="h-4 w-4 text-accent focus:ring-accent border-border">
                                        <span class="ml-2 text-sm text-text-primary">Auto-approve all comments</span>
                                    </label>
                                    <p class="text-xs text-text-secondary ml-6">Automatically approve all comments without moderation</p>
                                </div>
                            </div>
                        </div>

                        {{-- Media Settings --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Media Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="media_max_size" class="block text-sm font-medium text-text-secondary mb-2">Max File Size (MB)</label>
                                    <input type="number" name="media_max_size" id="media_max_size" 
                                           value="{{ old('media_max_size', setting('content.media_max_size', 10)) }}" 
                                           min="1" max="100"
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent">
                                    <p class="mt-1 text-xs text-text-secondary">Maximum file size for media uploads in megabytes</p>
                                </div>

                                <div>
                                    <label for="allowed_file_types" class="block text-sm font-medium text-text-secondary mb-2">Allowed File Types</label>
                                    <input type="text" name="allowed_file_types" id="allowed_file_types" 
                                           value="{{ old('allowed_file_types', setting('content.allowed_file_types', 'jpg,jpeg,png,gif,svg,webp,pdf,doc,docx,txt')) }}" 
                                           class="w-full bg-background border-border rounded-md shadow-sm text-text-primary focus:ring-accent focus:border-accent"
                                           placeholder="jpg,jpeg,png,gif,svg,webp,pdf">
                                    <p class="mt-1 text-xs text-text-secondary">Comma-separated list of allowed file extensions</p>
                                </div>
                            </div>
                        </div>

                        {{-- Content Guidelines --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Content Guidelines</h3>
                            <div class="bg-background border border-border rounded-lg p-4">
                                <h4 class="font-medium text-text-primary mb-2">Current Settings Summary</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-text-secondary">Posts per page:</span>
                                        <span class="text-text-primary font-medium">{{ setting('content.posts_per_page', 10) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-text-secondary">Comments enabled:</span>
                                        <span class="text-text-primary font-medium">{{ setting('content.comments_enabled', true) ? 'Yes' : 'No' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-text-secondary">Guest comments:</span>
                                        <span class="text-text-primary font-medium">{{ setting('content.comments_guest_allowed', true) ? 'Allowed' : 'Not allowed' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-text-secondary">Moderation required:</span>
                                        <span class="text-text-primary font-medium">{{ setting('content.comments_moderation', true) ? 'Yes' : 'No' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-text-secondary">Max file size:</span>
                                        <span class="text-text-primary font-medium">{{ setting('content.media_max_size', 10) }}MB</span>
                                    </div>
                                    <div>
                                        <span class="text-text-secondary">Default post template:</span>
                                        <span class="text-text-primary font-medium">{{ ucfirst(setting('content.default_post_template', 'default')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Content Management Tips --}}
                        <div>
                            <h3 class="text-lg font-medium text-text-primary mb-4">Content Management Tips</h3>
                            <div class="bg-accent/10 border border-accent/20 rounded-lg p-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Enable comment moderation for better content quality and spam prevention.</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Set appropriate file size limits based on your hosting capabilities.</span>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-accent mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-text-secondary">Choose templates that match your site's design and user experience goals.</span>
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
                            Save Content Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
